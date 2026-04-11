# Fix Data Issues Plan

## Issue 1: Data Display Problem (42 in DB, 31 showing)
**Problem:** Filter `status='active'` menyembunyikan 11 data dari tampilan.

**Analysis Needed:**
- Check database count by status
- Check MapController query filter
- Decide: Show all data or only active?

**Solution:**
- If filter is the issue, modify query to show all or add filter UI
- Update MapController.php getDisasters method

**Files:**
- resq/app/Http/Controllers/MapController.php

---

## Issue 2: No New Data Since Date 9
**Problem:** Scheduler comparison logic menggunakan file-based prev_data.json yang selalu reset saat pod restart.

**Root Cause:**
- prev_data.json di /tmp (emptyDir) → hilang saat pod restart
- Scheduler selalu menganggap data pertama adalah "new"
- Comparison logic tidak akurat

**Solution:**
- Ubah ke database-based comparison
- Query database untuk data gempa terakhir
- Bandingkan dengan data dari BMKG
- Insert hanya jika benar-benar baru

**Files:**
- scheduler/scrap.py

---

## Issue 3: prev_data.json Lost on Restart
**Problem:** Volume emptyDir tidak persisten.

**Solution:**
- Ganti emptyDir dengan PersistentVolumeClaim
- Atau: Gunakan database sebagai source of truth (lihat Issue 2)

**Files:**
- k8s/scheduler-deployment.yaml

---

## Execution Order
1. Issue 2 first (fix scheduler logic - most critical)
2. Issue 3 (add persistence if still needed)
3. Issue 1 (verify data display)

## Verification Steps
- Check scheduler logs for "New earthquake data detected!"
- Check database count increasing
- Check UI showing all data
