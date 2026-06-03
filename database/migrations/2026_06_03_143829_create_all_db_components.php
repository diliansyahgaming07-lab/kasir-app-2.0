<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Nonaktifkan sementara foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // ========== 1. TABEL MEMBERS: TAMBAHKAN user_id (RELASI 1:1) ==========
        if (!Schema::hasColumn('members', 'user_id')) {
            Schema::table('members', function ($table) {
                $table->foreignId('user_id')->unique()->nullable()->after('id')
                      ->constrained('users')->onDelete('cascade');
            });
        }

        // ========== 2. STORED PROCEDURES (3) ==========
        DB::unprepared("DROP PROCEDURE IF EXISTS get_total_sales_by_date_range");
        DB::unprepared("
            CREATE PROCEDURE get_total_sales_by_date_range(IN start_date DATE, IN end_date DATE)
            BEGIN
                SELECT 
                    DATE(created_at) AS transaction_date,
                    COUNT(*) AS total_transactions,
                    SUM(total_amount) AS total_sales
                FROM transactions
                WHERE status = 'completed' AND DATE(created_at) BETWEEN start_date AND end_date
                GROUP BY DATE(created_at)
                ORDER BY transaction_date DESC;
            END
        ");

        DB::unprepared("DROP PROCEDURE IF EXISTS get_top_products");
        DB::unprepared("
            CREATE PROCEDURE get_top_products(IN limit_count INT)
            BEGIN
                SELECT 
                    p.id, p.name, SUM(td.quantity) AS total_sold,
                    SUM(td.subtotal) AS total_revenue
                FROM products p
                JOIN transaction_details td ON p.id = td.product_id
                JOIN transactions t ON td.transaction_id = t.id
                WHERE t.status = 'completed'
                GROUP BY p.id, p.name
                ORDER BY total_sold DESC
                LIMIT limit_count;
            END
        ");

        DB::unprepared("DROP PROCEDURE IF EXISTS get_customer_orders");
        DB::unprepared("
            CREATE PROCEDURE get_customer_orders(IN customer_id INT)
            BEGIN
                SELECT invoice_number, total_amount, payment_method, order_status, created_at
                FROM transactions
                WHERE user_id = customer_id
                ORDER BY created_at DESC;
            END
        ");

        // ========== 3. TRIGGERS (3) ==========
        DB::unprepared("DROP TRIGGER IF EXISTS after_transaction_insert");
        DB::unprepared("
            CREATE TRIGGER after_transaction_insert
            AFTER INSERT ON transactions
            FOR EACH ROW
            BEGIN
                IF NEW.member_id IS NOT NULL AND NEW.status = 'completed' THEN
                    UPDATE members 
                    SET total_spent = total_spent + NEW.total_amount,
                        points = points + FLOOR(NEW.total_amount / 10000)
                    WHERE id = NEW.member_id;
                END IF;
            END
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS before_product_delete");
        DB::unprepared("
            CREATE TRIGGER before_product_delete
            BEFORE DELETE ON products
            FOR EACH ROW
            BEGIN
                DECLARE transaction_count INT;
                SELECT COUNT(*) INTO transaction_count FROM transaction_details WHERE product_id = OLD.id;
                IF transaction_count > 0 THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Tidak dapat menghapus produk yang sudah memiliki transaksi';
                END IF;
            END
        ");

        // Buat tabel log terlebih dahulu tanpa foreign key
        DB::unprepared("
            CREATE TABLE IF NOT EXISTS transaction_status_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                transaction_id BIGINT UNSIGNED NOT NULL,
                old_status VARCHAR(50),
                new_status VARCHAR(50),
                changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Tambahkan foreign key setelah tabel ada
        DB::unprepared("
            ALTER TABLE transaction_status_logs 
            ADD CONSTRAINT transaction_status_logs_transaction_id_foreign 
            FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE;
        ");

        DB::unprepared("DROP TRIGGER IF EXISTS after_transaction_status_update");
        DB::unprepared("
            CREATE TRIGGER after_transaction_status_update
            AFTER UPDATE ON transactions
            FOR EACH ROW
            BEGIN
                IF OLD.order_status != NEW.order_status THEN
                    INSERT INTO transaction_status_logs (transaction_id, old_status, new_status, changed_at)
                    VALUES (NEW.id, OLD.order_status, NEW.order_status, NOW());
                END IF;
            END
        ");

        // ========== 4. VIEWS (3) ==========
        DB::unprepared("DROP VIEW IF EXISTS sales_report");
        DB::unprepared("
            CREATE VIEW sales_report AS
            SELECT 
                t.id, t.invoice_number, t.customer_name, t.phone, t.shipping_address,
                t.total_amount, t.payment_method, t.order_status, t.created_at AS order_date,
                COUNT(td.id) AS total_items, u.name AS cashier_name
            FROM transactions t
            LEFT JOIN transaction_details td ON t.id = td.transaction_id
            LEFT JOIN users u ON t.user_id = u.id
            GROUP BY t.id, t.invoice_number, t.customer_name, t.phone, t.shipping_address,
                     t.total_amount, t.payment_method, t.order_status, t.created_at, u.name
            ORDER BY t.created_at DESC;
        ");

        DB::unprepared("DROP VIEW IF EXISTS customer_order_summary");
        DB::unprepared("
            CREATE VIEW customer_order_summary AS
            SELECT 
                u.id AS user_id, u.name AS customer_name, u.email,
                COUNT(t.id) AS total_orders,
                COALESCE(SUM(t.total_amount), 0) AS total_spent,
                MAX(t.created_at) AS last_order_date
            FROM users u
            LEFT JOIN transactions t ON u.id = t.user_id AND t.status = 'completed'
            WHERE u.role = 'customer'
            GROUP BY u.id, u.name, u.email;
        ");

        DB::unprepared("DROP VIEW IF EXISTS product_stock_view");
        DB::unprepared("
            CREATE VIEW product_stock_view AS
            SELECT 
                p.id, p.name, p.barcode, p.stock, c.name AS category_name,
                CASE 
                    WHEN p.stock <= 0 THEN 'Habis'
                    WHEN p.stock <= 5 THEN 'Sangat Terbatas'
                    WHEN p.stock <= 10 THEN 'Terbatas'
                    ELSE 'Cukup'
                END AS stock_status
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.stock <= 10
            ORDER BY p.stock ASC;
        ");

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        DB::unprepared("DROP VIEW IF EXISTS sales_report");
        DB::unprepared("DROP VIEW IF EXISTS customer_order_summary");
        DB::unprepared("DROP VIEW IF EXISTS product_stock_view");
        DB::unprepared("DROP PROCEDURE IF EXISTS get_total_sales_by_date_range");
        DB::unprepared("DROP PROCEDURE IF EXISTS get_top_products");
        DB::unprepared("DROP PROCEDURE IF EXISTS get_customer_orders");
        DB::unprepared("DROP TRIGGER IF EXISTS after_transaction_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS before_product_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS after_transaction_status_update");
        DB::unprepared("DROP TABLE IF EXISTS transaction_status_logs");
        
        if (Schema::hasColumn('members', 'user_id')) {
            Schema::table('members', function ($table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};