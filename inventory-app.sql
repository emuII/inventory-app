-- MATIKAN FK DULU
SET FOREIGN_KEY_CHECKS = 0;

-- =========================
-- 1. DROP SEMUA FOREIGN KEY
-- =========================

ALTER TABLE `approval_request`
  DROP FOREIGN KEY `fk_ar_approver_member`,
  DROP FOREIGN KEY `fk_ar_pr`,
  DROP FOREIGN KEY `fk_ar_status`;

ALTER TABLE `purchase_request`
  DROP FOREIGN KEY `fk_pr_status`,
  DROP FOREIGN KEY `fk_pr_supplier`,
  DROP FOREIGN KEY `fk_pr_user`;

ALTER TABLE `purchase_request_detail`
  DROP FOREIGN KEY `fk_prd_item`,
  DROP FOREIGN KEY `fk_prd_pr`;

ALTER TABLE `warehouse`
  DROP FOREIGN KEY `fk_warehose_status`,
  DROP FOREIGN KEY `fk_warehose_supplier`,
  DROP FOREIGN KEY `fk_warehouse_pr`;

ALTER TABLE `warehouse_detail`
  DROP FOREIGN KEY `fk_wdetail_warehouse`,
  DROP FOREIGN KEY `fk_whdetail_item`,
  DROP FOREIGN KEY `fk_whdetail_prd`;

ALTER TABLE `warehouse_history`
  DROP FOREIGN KEY `fk_whistory_warehouse`,
  DROP FOREIGN KEY `fk_whistory_warehouse_d`;

-- ==============================
-- 2. TRUNCATE TABLE YANG DIPILIH
--    (TIDAK DI-TRUNCATE: 
--    m_store, m_user, m_item, 
--    approval_member, m_supplier, m_bank)
-- ==============================

TRUNCATE TABLE `approval_request`;
TRUNCATE TABLE `delivery_detail`;
TRUNCATE TABLE `delivery_order`;
TRUNCATE TABLE `m_status`;
TRUNCATE TABLE `purchase_request`;
TRUNCATE TABLE `purchase_request_detail`;
TRUNCATE TABLE `warehouse`;
TRUNCATE TABLE `warehouse_detail`;
TRUNCATE TABLE `warehouse_history`;

-- =========================
-- 3. GENERATE ULANG FOREIGN KEY
-- =========================

ALTER TABLE `approval_request`
  ADD CONSTRAINT `fk_ar_approver_member` FOREIGN KEY (`approver_id`) REFERENCES `approval_member` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ar_pr` FOREIGN KEY (`pr_id`) REFERENCES `purchase_request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ar_status` FOREIGN KEY (`status`) REFERENCES `m_status` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `purchase_request`
  ADD CONSTRAINT `fk_pr_status` FOREIGN KEY (`status`) REFERENCES `m_status` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pr_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `m_supplier` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pr_user` FOREIGN KEY (`requester_id`) REFERENCES `m_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `purchase_request_detail`
  ADD CONSTRAINT `fk_prd_item` FOREIGN KEY (`item_id`) REFERENCES `m_item` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prd_pr` FOREIGN KEY (`pr_id`) REFERENCES `purchase_request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `warehouse`
  ADD CONSTRAINT `fk_warehose_status` FOREIGN KEY (`status`) REFERENCES `m_status` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_warehose_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `m_supplier` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_warehouse_pr` FOREIGN KEY (`pr_id`) REFERENCES `purchase_request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `warehouse_detail`
  ADD CONSTRAINT `fk_wdetail_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_whdetail_item` FOREIGN KEY (`item_id`) REFERENCES `m_item` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_whdetail_prd` FOREIGN KEY (`prd_id`) REFERENCES `purchase_request_detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `warehouse_history`
  ADD CONSTRAINT `fk_whistory_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_whistory_warehouse_d` FOREIGN KEY (`warehouse_detail_id`) REFERENCES `warehouse_detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- HIDUPKAN LAGI FK
SET FOREIGN_KEY_CHECKS = 1;
