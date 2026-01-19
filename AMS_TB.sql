CREATE TABLE "ast_items" (
  "item_id" serial PRIMARY KEY,
  "nama" varchar,
  "merk" varchar,
  "qty" numeric,
  "satuan_qty" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "ast_consume_items" (
  "citem_id" serial PRIMARY KEY,
  "item_id" integer,
  "qty" numeric,
  "satuan_qty" varchar,
  "keterangan" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "ast_buy_items" (
  "bitem_id" serial PRIMARY KEY,
  "item_id" integer,
  "qty" numeric,
  "satuan_qty" varchar,
  "keterangan" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "ast_asset" (
  "asset_id" serial PRIMARY KEY,
  "kode_asset" varchar,
  "nama_asset" varchar,
  "merk" varchar,
  "tipe" varchar,
  "spesifikasi" text,
  "serial_number" varchar,
  "kategori_id" integer,
  "mobile" bool,
  "lampiran" varchar,
  "status" varchar,
  "kondisi" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "ast_permohonan_asset" (
  "id" serial PRIMARY KEY,
  "nomor_permohonan" varchar,
  "departemen" varchar,
  "keterangan_tujuan" text,
  "total_anggaran" numeric,
  "pemohon" varchar,
  "status" integer,
  "approved_at" timestamp,
  "approved_by" varchar,
  "rejected_at" timestampp,
  "rejected_by" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "ast_permohonan_asset_detail" (
  "id" serial PRIMARY KEY,
  "permohonan_id" integer,
  "nomor_permohonan" varchar,
  "kelompok_id" integer,
  "nama_asset" varchar,
  "keterangan" text,
  "harga" numeric,
  "qty" numeric,
  "lampiran_file" varchar
);

CREATE TABLE "ast_pembelian_asset" (
  "id" serial PRIMARY KEY,
  "nomor_pembelian" varchar,
  "total_anggaran" numeric,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "ast_pembelian_asset_detail" (
  "id" serial PRIMARY KEY,
  "kategori_id" integer,
  "kelompok_id" integer,
  "nama_item" varchar
);

CREATE TABLE "ast_penerimaan_asset" (
  "id" serial PRIMARY KEY,
  "id_pembelian" integer,
  "invoice_number" varchar,
  "nomor_pembelian" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "ast_penerimaan_asset_detail" (
  "id" serial PRIMARY KEY,
  "id_penerimaan" integer,
  "invoice_number" varchar,
  "kategori_id" integer,
  "kelompok_id" integer,
  "nama_item" varchar,
  "qty" numeric,
  "qty_gr" numeric
);

CREATE TABLE "ast_maintenance_asset" (
  "id" serial PRIMARY KEY,
  "asset_id" integer,
  "keterangan" varchar,
  "tglperbaikan" date,
  "tglselesaiperbaikan" date,
  "invoice" varchar,
  "anggaran" numeric,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "ast_distribusi_asset" (
  "id" serial PRIMARY KEY,
  "asset_id" integer,
  "lokasi_id" integer,
  "tgldistribusi" timestamp,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "dt_kelompok_asset" (
  "kelompok_id" serial PRIMARY KEY,
  "nama_kelompok" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "dt_kategori_asset" (
  "id" serial PRIMARY KEY,
  "nama_kategori" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "dt_supplier" (
  "id" serial PRIMARY KEY,
  "nama_supplier" varchar,
  "alamat" text,
  "nomortelepon" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "dt_departemen" (
  "id" serial PRIMARY KEY,
  "nama_departemen" varchar,
  "deskripsi" text,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "dt_lokasi" (
  "id" serial PRIMARY KEY,
  "level" integer,
  "nama" varchar,
  "induk" integer,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "pjm_peminjaman_asset" (
  "id" serial PRIMARY KEY,
  "asset_id" integer,
  "peminjam" varchar,
  "tglpinjam" timestamp,
  "tglpengembalian" timestamp,
  "durasipinjam" numeric,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "rpt_report" (
  "id" serial PRIMARY KEY,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "ba_berita_acara" (
  "id" serial PRIMARY KEY,
  "jenis_berita" integer,
  "keterangan" varchar,
  "asset_id" integer,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "usr_user" (
  "id" serial PRIMARY KEY,
  "username" varchar,
  "password" varchar,
  "email" varchar,
  "nama" varchar,
  "nohp" varchar,
  "nomor_registrasi" varchar,
  "departemen_id" integer,
  "role_id" integer,
  "active" bool,
  "token" varchar
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "menus" (
  "menu_id" serial PRIMARY KEY,
  "parent_id" integer,
  "nama" varchar,
  "url" varchar,
  "icon" varchar,
  "level" numeric,
  "pos" numeric,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "usr_role" (
  "role_id" serial PRIMARY KEY,
  "nama" varchar,
  "keterangan" varchar,
  "created_at" timestamp,
  "created_by" varchar,
  "updated_at" timestamp,
  "updated_by" varchar,
  "is_deleted" bool,
  "deleted_at" timestamp,
  "deleted_by" varchar
);

CREATE TABLE "usr_otorisasi_menu" (
  "id" serial PRIMARY KEY,
  "role_id" integer,
  "menu_id" integer,
  "created_at" timestamp,
  "created_by" varchar
);