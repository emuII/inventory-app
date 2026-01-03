-- ==============================================
-- SCRIPT RESET DATABASE INVENTORY-APPS
-- ==============================================

-- 1. Nonaktifkan foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- 2. Hapus semua foreign key constraints
ALTER TABLE `approval_request` DROP FOREIGN KEY `fk_ar_approver_member`;
ALTER TABLE `approval_request` DROP FOREIGN KEY `fk_ar_pr`;
ALTER TABLE `approval_request` DROP FOREIGN KEY `fk_ar_status`;

ALTER TABLE `delivery_order_detail` DROP FOREIGN KEY `fk_do`;
ALTER TABLE `delivery_order_detail` DROP FOREIGN KEY `fk_item`;

ALTER TABLE `purchase_request` DROP FOREIGN KEY `fk_pr_status`;
ALTER TABLE `purchase_request` DROP FOREIGN KEY `fk_pr_supplier`;
ALTER TABLE `purchase_request` DROP FOREIGN KEY `fk_pr_user`;

ALTER TABLE `purchase_request_detail` DROP FOREIGN KEY `fk_prd_item`;
ALTER TABLE `purchase_request_detail` DROP FOREIGN KEY `fk_prd_pr`;

ALTER TABLE `warehouse` DROP FOREIGN KEY `fk_warehose_status`;
ALTER TABLE `warehouse` DROP FOREIGN KEY `fk_warehose_supplier`;
ALTER TABLE `warehouse` DROP FOREIGN KEY `fk_warehouse_pr`;

ALTER TABLE `warehouse_detail` DROP FOREIGN KEY `fk_wdetail_warehouse`;
ALTER TABLE `warehouse_detail` DROP FOREIGN KEY `fk_whdetail_item`;
ALTER TABLE `warehouse_detail` DROP FOREIGN KEY `fk_whdetail_prd`;

ALTER TABLE `warehouse_history` DROP FOREIGN KEY `fk_whistory_warehouse`;
ALTER TABLE `warehouse_history` DROP FOREIGN KEY `fk_whistory_warehouse_d`;

-- 3. TRUNCATE semua tabel kecuali yang dikecualikan
TRUNCATE TABLE `approval_request`;
TRUNCATE TABLE `delivery_order`;
TRUNCATE TABLE `delivery_order_detail`;
TRUNCATE TABLE `m_bank`;
TRUNCATE TABLE `m_item`;
TRUNCATE TABLE `purchase_request`;
TRUNCATE TABLE `purchase_request_detail`;
TRUNCATE TABLE `warehouse`;
TRUNCATE TABLE `warehouse_detail`;
TRUNCATE TABLE `warehouse_history`;

-- 4. Reset AUTO_INCREMENT
ALTER TABLE `approval_request` AUTO_INCREMENT = 1;
ALTER TABLE `delivery_order` AUTO_INCREMENT = 1;
ALTER TABLE `delivery_order_detail` AUTO_INCREMENT = 1;
ALTER TABLE `m_bank` AUTO_INCREMENT = 1;
ALTER TABLE `m_item` AUTO_INCREMENT = 1;
ALTER TABLE `purchase_request` AUTO_INCREMENT = 1;
ALTER TABLE `purchase_request_detail` AUTO_INCREMENT = 1;
ALTER TABLE `warehouse` AUTO_INCREMENT = 1;
ALTER TABLE `warehouse_detail` AUTO_INCREMENT = 1;
ALTER TABLE `warehouse_history` AUTO_INCREMENT = 1;

-- 5. Tambah kembali semua foreign key constraints
-- approval_request
ALTER TABLE `approval_request` 
ADD CONSTRAINT `fk_ar_approver_member` 
FOREIGN KEY (`approver_id`) 
REFERENCES `approval_member` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `approval_request` 
ADD CONSTRAINT `fk_ar_pr` 
FOREIGN KEY (`pr_id`) 
REFERENCES `purchase_request` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `approval_request` 
ADD CONSTRAINT `fk_ar_status` 
FOREIGN KEY (`status`) 
REFERENCES `m_status` (`Id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- delivery_order_detail
ALTER TABLE `delivery_order_detail` 
ADD CONSTRAINT `fk_do` 
FOREIGN KEY (`do_id`) 
REFERENCES `delivery_order` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `delivery_order_detail` 
ADD CONSTRAINT `fk_item` 
FOREIGN KEY (`item_id`) 
REFERENCES `m_item` (`Id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- purchase_request
ALTER TABLE `purchase_request` 
ADD CONSTRAINT `fk_pr_status` 
FOREIGN KEY (`status`) 
REFERENCES `m_status` (`Id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `purchase_request` 
ADD CONSTRAINT `fk_pr_supplier` 
FOREIGN KEY (`supplier_id`) 
REFERENCES `m_supplier` (`Id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `purchase_request` 
ADD CONSTRAINT `fk_pr_user` 
FOREIGN KEY (`requester_id`) 
REFERENCES `m_user` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- purchase_request_detail
ALTER TABLE `purchase_request_detail` 
ADD CONSTRAINT `fk_prd_item` 
FOREIGN KEY (`item_id`) 
REFERENCES `m_item` (`Id`) 
ON UPDATE CASCADE;

ALTER TABLE `purchase_request_detail` 
ADD CONSTRAINT `fk_prd_pr` 
FOREIGN KEY (`pr_id`) 
REFERENCES `purchase_request` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- warehouse
ALTER TABLE `warehouse` 
ADD CONSTRAINT `fk_warehose_status` 
FOREIGN KEY (`status`) 
REFERENCES `m_status` (`Id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `warehouse` 
ADD CONSTRAINT `fk_warehose_supplier` 
FOREIGN KEY (`supplier_id`) 
REFERENCES `m_supplier` (`Id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `warehouse` 
ADD CONSTRAINT `fk_warehouse_pr` 
FOREIGN KEY (`pr_id`) 
REFERENCES `purchase_request` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- warehouse_detail
ALTER TABLE `warehouse_detail` 
ADD CONSTRAINT `fk_wdetail_warehouse` 
FOREIGN KEY (`warehouse_id`) 
REFERENCES `warehouse` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `warehouse_detail` 
ADD CONSTRAINT `fk_whdetail_item` 
FOREIGN KEY (`item_id`) 
REFERENCES `m_item` (`Id`) 
ON UPDATE CASCADE;

ALTER TABLE `warehouse_detail` 
ADD CONSTRAINT `fk_whdetail_prd` 
FOREIGN KEY (`prd_id`) 
REFERENCES `purchase_request_detail` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- warehouse_history
ALTER TABLE `warehouse_history` 
ADD CONSTRAINT `fk_whistory_warehouse` 
FOREIGN KEY (`warehouse_id`) 
REFERENCES `warehouse` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `warehouse_history` 
ADD CONSTRAINT `fk_whistory_warehouse_d` 
FOREIGN KEY (`warehouse_detail_id`) 
REFERENCES `warehouse_detail` (`id`) 
ON DELETE CASCADE ON UPDATE CASCADE;

-- 6. Aktifkan kembali foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- 7. Verifikasi data yang tetap ada
SELECT 
    'approval_member' as table_name, 
    COUNT(*) as row_count 
FROM `approval_member`
UNION ALL
SELECT 'm_user', COUNT(*) FROM `m_user`
UNION ALL
SELECT 'm_status', COUNT(*) FROM `m_status`
UNION ALL
SELECT 'm_store', COUNT(*) FROM `m_store`
UNION ALL
SELECT 'm_supplier', COUNT(*) FROM `m_supplier`;

-- 8. Tampilkan status tabel yang telah di-truncate
SELECT 
    'approval_request' as table_name, 
    COUNT(*) as row_count 
FROM `approval_request`
UNION ALL
SELECT 'delivery_order', COUNT(*) FROM `delivery_order`
UNION ALL
SELECT 'delivery_order_detail', COUNT(*) FROM `delivery_order_detail`
UNION ALL
SELECT 'm_bank', COUNT(*) FROM `m_bank`
UNION ALL
SELECT 'm_item', COUNT(*) FROM `m_item`
UNION ALL
SELECT 'purchase_request', COUNT(*) FROM `purchase_request`
UNION ALL
SELECT 'purchase_request_detail', COUNT(*) FROM `purchase_request_detail`
UNION ALL
SELECT 'warehouse', COUNT(*) FROM `warehouse`
UNION ALL
SELECT 'warehouse_detail', COUNT(*) FROM `warehouse_detail`
UNION ALL
SELECT 'warehouse_history', COUNT(*) FROM `warehouse_history`;