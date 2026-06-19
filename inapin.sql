-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 03 Jun 2026 pada 16.03
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inapin`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@inapin.com', '$2y$12$CIhVYloIN4PyL9.1QkuZbO5yhiyh0MmXgfJEQSTcZEOOjFKb/N0dW', 'jiOOB41t6OG8uMbWNephQRsTVfl6Gsw47kHWf2tfOPGcspTgmxUBQxRwgORz', '2026-06-02 01:00:46', '2026-06-02 01:00:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
--

CREATE TABLE `customer` (
  `id_customer` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id_customer`, `nama`, `email`, `password`, `phone`, `tanggal_lahir`, `foto`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Dhaniel Faturahman', 'faturahmandaniel87@gmail.com', '$2y$12$LKHTlaySNtiWAibFoLfS7.Tks7AOggMFe9sd0jNewcWC1KgKNxn6S', '08886132297', '2026-06-02', NULL, '4bXsutA3iDap76k1tp7ve7pmhmlOIo6E3cg9F8fQm4abN1ydCngoMRiYZM6d', '2026-06-02 01:19:26', '2026-06-02 01:19:26'),
(2, 'Bintang', 'star123@gmail.com', '$2y$12$B0XEbJkHIIgYF32JsEkdAOEOOClhNaVTcWV/vj0CdsUVoClQ9WJ8G', '089980779909', '2003-07-17', NULL, 'WqoThm7A0M2EuWN8FPTWr568sqrV369Qa72WSm0OHHB2WeNISsGz5oPdTqoq', '2026-06-03 05:08:49', '2026-06-03 05:08:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pemesanan`
--

CREATE TABLE `detail_pemesanan` (
  `id_detail` bigint UNSIGNED NOT NULL,
  `id_pemesanan` bigint UNSIGNED NOT NULL,
  `id_pembayaran` bigint UNSIGNED DEFAULT NULL,
  `tanggal_checkin` date NOT NULL,
  `tanggal_checkout` date NOT NULL,
  `harga_default` decimal(15,2) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_pemesanan`
--

INSERT INTO `detail_pemesanan` (`id_detail`, `id_pemesanan`, `id_pembayaran`, `tanggal_checkin`, `tanggal_checkout`, `harga_default`, `sub_total`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '2026-06-02', '2026-06-03', '99998.00', '99998.00', '2026-06-02 01:23:00', '2026-06-02 01:23:00'),
(2, 2, NULL, '2026-06-04', '2026-06-06', '1000000.00', '2000000.00', '2026-06-03 05:31:03', '2026-06-03 05:31:03'),
(3, 3, NULL, '2026-06-03', '2026-06-11', '123456789.00', '987654312.00', '2026-06-03 06:37:58', '2026-06-03 06:37:58'),
(4, 4, NULL, '2026-06-03', '2026-06-11', '123456789.00', '987654312.00', '2026-06-03 06:38:49', '2026-06-03 06:38:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen_villa`
--

CREATE TABLE `dokumen_villa` (
  `id_dokumen_villa` bigint UNSIGNED NOT NULL,
  `id_villa` bigint UNSIGNED NOT NULL,
  `id_owner` bigint UNSIGNED DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disetujui',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dokumen_villa`
--

INSERT INTO `dokumen_villa` (`id_dokumen_villa`, `id_villa`, `id_owner`, `file_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'foto_villa/I9rCtyKJ58RTduZg4lFoX7fnGHM66MKR7yaetiBM.jpg', 'disetujui', '2026-06-02 01:04:33', '2026-06-02 01:04:33'),
(2, 5, 1, 'foto_villa/iSocipYqCRk0QEX02wnNDea6lNM7wdKlgOChMyrY.png', 'disetujui', '2026-06-03 04:50:17', '2026-06-03 04:50:17'),
(3, 6, 2, 'foto_villa/fnVYZVhNXjycpJsnrt2EwbSwDntlCe6YP0Raj8A0.png', 'disetujui', '2026-06-03 05:48:50', '2026-06-03 05:48:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fasilitas_villa`
--

CREATE TABLE `fasilitas_villa` (
  `id_fasilitas` bigint UNSIGNED NOT NULL,
  `id_villa` bigint UNSIGNED NOT NULL,
  `fasilitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `fasilitas_villa`
--

INSERT INTO `fasilitas_villa` (`id_fasilitas`, `id_villa`, `fasilitas`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kolam Renang', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(2, 1, 'WiFi Gratis', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(3, 1, 'Sarapan', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(4, 1, 'Parkir Luas', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(5, 1, 'AC Setiap Kamar', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(6, 2, 'Kolam Renang', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(7, 2, 'WiFi Gratis', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(8, 2, 'Sarapan', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(9, 2, 'Parkir Luas', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(10, 2, 'AC Setiap Kamar', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(11, 3, 'Kolam Renang', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(12, 3, 'WiFi Gratis', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(13, 3, 'Sarapan', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(14, 3, 'Parkir Luas', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(15, 3, 'AC Setiap Kamar', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(28, 4, 'Kolam Renang', '2026-06-02 01:28:31', '2026-06-02 01:28:31'),
(29, 4, 'WiFi Gratis', '2026-06-02 01:28:31', '2026-06-02 01:28:31'),
(30, 4, 'Dapur Lengkap', '2026-06-02 01:28:31', '2026-06-02 01:28:31'),
(31, 4, 'Parkir Luas', '2026-06-02 01:28:31', '2026-06-02 01:28:31'),
(32, 4, 'AC Setiap Kamar', '2026-06-02 01:28:31', '2026-06-02 01:28:31'),
(33, 4, 'BBQ Area', '2026-06-02 01:28:31', '2026-06-02 01:28:31'),
(40, 5, 'Kolam Renang', '2026-06-03 04:55:39', '2026-06-03 04:55:39'),
(41, 5, 'WiFi Gratis', '2026-06-03 04:55:39', '2026-06-03 04:55:39'),
(42, 5, 'Dapur Lengkap', '2026-06-03 04:55:39', '2026-06-03 04:55:39'),
(43, 5, 'Parkir Luas', '2026-06-03 04:55:39', '2026-06-03 04:55:39'),
(44, 5, 'AC Setiap Kamar', '2026-06-03 04:55:39', '2026-06-03 04:55:39'),
(45, 5, 'BBQ Area', '2026-06-03 04:55:39', '2026-06-03 04:55:39'),
(46, 6, 'Kolam Renang', '2026-06-03 05:48:50', '2026-06-03 05:48:50'),
(47, 6, 'WiFi Gratis', '2026-06-03 05:48:50', '2026-06-03 05:48:50'),
(48, 6, 'Dapur Lengkap', '2026-06-03 05:48:50', '2026-06-03 05:48:50'),
(49, 6, 'Parkir Luas', '2026-06-03 05:48:50', '2026-06-03 05:48:50'),
(50, 6, 'AC Setiap Kamar', '2026-06-03 05:48:50', '2026-06-03 05:48:50'),
(51, 6, 'BBQ Area', '2026-06-03 05:48:50', '2026-06-03 05:48:50'),
(52, 7, 'Kolam Renang', '2026-06-03 06:31:49', '2026-06-03 06:31:49'),
(53, 7, 'WiFi Gratis', '2026-06-03 06:31:49', '2026-06-03 06:31:49'),
(54, 7, 'Dapur Lengkap', '2026-06-03 06:31:49', '2026-06-03 06:31:49'),
(55, 7, 'Parkir Luas', '2026-06-03 06:31:49', '2026-06-03 06:31:49'),
(56, 7, 'AC Setiap Kamar', '2026-06-03 06:31:49', '2026-06-03 06:31:49'),
(57, 7, 'BBQ Area', '2026-06-03 06:31:49', '2026-06-03 06:31:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_05_02_090351_create_villa_table', 1),
(6, '2026_05_02_090404_create_fasilitas_villa_table', 1),
(7, '2026_05_02_090418_create_dokumen_villa_table', 1),
(8, '2026_05_02_151833_create_customer_table', 1),
(9, '2026_05_02_153417_create_pemesanan_table', 1),
(10, '2026_05_02_153426_create_detail_pemesanan_table', 1),
(11, '2026_05_03_123413_create_owner_table', 1),
(12, '2026_05_08_185909_create_admin_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `owner`
--

CREATE TABLE `owner` (
  `id_owner` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `owner`
--

INSERT INTO `owner` (`id_owner`, `nama`, `email`, `password`, `phone`, `alamat`, `nik`, `tanggal_lahir`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Dhaniel Faturahman', 'daniel@gmail.com', '$2y$12$hJzaYAwKjOqElaBFxwCajOm2u4Ecxji7PgErseoax98vOXX6OdH1K', '08886132297', 'Perumahan Villa Mahkota Indah Blok A3 No.12', '1234567887654321', '2000-06-02', 'M1rXjXLNxd8Xo67daHEVGF4cVSiBbF9JRfJbD4vny8RU8n2NcRadsTTXb5nQ', '2026-06-02 01:02:57', '2026-06-02 01:02:57'),
(2, 'Oktaner', 'ner123@gmail.com', '$2y$12$R7cs9kLZBpd9B1qkxzBnqOLVfEGjKra1WW1IuyvBL5.4C2L4cS686', '089517847356', 'Jalan rumah owner', '6212345678910112', '1999-08-17', NULL, '2026-06-03 05:35:44', '2026-06-03 05:35:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` bigint UNSIGNED NOT NULL,
  `id_villa` bigint UNSIGNED NOT NULL,
  `id_customer` bigint UNSIGNED NOT NULL,
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pemesanan` datetime NOT NULL,
  `status` enum('menunggu','dibayar','checked_in','checked_out','dibatalkan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_villa`, `id_customer`, `metode_pembayaran`, `tanggal_pemesanan`, `status`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'midtrans', '2026-06-02 08:23:00', 'dibatalkan', '2026-06-02 08:53:00', '2026-06-02 01:23:00', '2026-06-02 01:23:29'),
(2, 5, 2, 'midtrans', '2026-06-03 12:31:03', 'dibayar', NULL, '2026-06-03 05:31:03', '2026-06-03 05:31:44'),
(3, 7, 2, 'midtrans', '2026-06-03 13:37:58', 'menunggu', '2026-06-03 14:07:58', '2026-06-03 06:37:58', '2026-06-03 06:37:58'),
(4, 7, 2, 'midtrans', '2026-06-03 13:38:49', 'menunggu', '2026-06-03 14:08:49', '2026-06-03 06:38:49', '2026-06-03 06:38:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` bigint UNSIGNED NOT NULL,
  `id_villa` bigint UNSIGNED NOT NULL,
  `id_customer` bigint UNSIGNED NOT NULL,
  `rating` tinyint NOT NULL,
  `komentar` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `villa`
--

CREATE TABLE `villa` (
  `id_villa` bigint UNSIGNED NOT NULL,
  `id_owner` bigint UNSIGNED DEFAULT NULL,
  `nama_villa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `kota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelurahan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL,
  `kapasitas` int NOT NULL,
  `jumlah_kamar` int NOT NULL DEFAULT '1',
  `jumlah_kamar_mandi` int NOT NULL DEFAULT '1',
  `status` enum('pending','disetujui','ditolak','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `tersedia` tinyint(1) NOT NULL DEFAULT '1',
  `ulasan` decimal(3,1) DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiktok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `villa`
--

INSERT INTO `villa` (`id_villa`, `id_owner`, `nama_villa`, `deskripsi`, `kota`, `kelurahan`, `kecamatan`, `provinsi`, `latitude`, `longitude`, `harga`, `kapasitas`, `jumlah_kamar`, `jumlah_kamar_mandi`, `status`, `tersedia`, `ulasan`, `alamat`, `whatsapp`, `instagram`, `facebook`, `tiktok`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Villa Bukit Hijau', 'Villa mewah dengan pemandangan bukit hijau yang memukau, cocok untuk keluarga besar.', 'Bogor', NULL, NULL, NULL, NULL, NULL, '1500000.00', 10, 4, 3, 'disetujui', 1, '4.8', 'Jl. Raya Puncak No. 12, Bogor, Jawa Barat', '081234567890', '@villabukit_puncak', 'villabukit.puncak', '@villabukit_official', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(2, NULL, 'Villa Sawah Jogja', 'Suasana pedesaan Jawa yang autentik dengan hamparan sawah hijau nan menenangkan.', 'Yogyakarta', NULL, NULL, NULL, NULL, NULL, '900000.00', 8, 4, 2, 'disetujui', 1, '4.6', 'Jl. Parangtritis KM 14, Bantul, Yogyakarta', '084567890123', '@villasawah_jogja', 'villasawahjogja', NULL, '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(3, NULL, 'Villa Lembang Sejuk', 'Villa nyaman dengan udara sejuk khas dataran tinggi Lembang dan kolam renang air hangat.', 'Bandung', NULL, NULL, NULL, NULL, NULL, '1800000.00', 12, 5, 4, 'disetujui', 1, '4.5', 'Jl. Lembang Indah No. 45, Lembang, Bandung Barat', '085678901234', '@villalembang_official', 'villalembang', '@villalembang_sejuk', '2026-06-02 01:00:46', '2026-06-02 01:00:46'),
(4, 1, 'villa vanilla', 'Ya', 'KABUPATEN BEKASI', 'Pahlawan Setia', 'Medan Satria', 'JAWA BARAT', NULL, NULL, '99998.00', 12, 1, 1, 'disetujui', 1, NULL, 'Perumahan Villa Mahkota Indah Blok A3 No.12', '0986654567', 'Yaaa', 'shabil', 'ya', '2026-06-02 01:04:32', '2026-06-02 01:28:31'),
(5, 1, 'Vila bogor', 'Y', 'Bandung', 'Karyawangi', 'Parongpong', 'Jawa Barat', NULL, NULL, '1000000.00', 10, 1, 1, 'disetujui', 0, NULL, 'Jl. Kolonel Masturi Km. 9', '081906751614', 'Villa Bogor', 'Villa Bogor', 'Villa Bogor', '2026-06-03 04:50:15', '2026-06-03 05:54:49'),
(6, 2, 'Villa Gardenclover', 'Gokil', 'Yogyakarta', 'Baciro', 'Gondokusuman', 'Daerah Istimewa Yogyakarta', NULL, NULL, '200000.00', 5, 3, 1, 'disetujui', 1, NULL, 'Jl. Melati Wetan No.42', '089517847356', 'theresiaacn', 'theresiaacn', 'theresiaacn', '2026-06-03 05:48:50', '2026-06-03 06:28:43'),
(7, 2, 'vila coba', 'coba', 'coba', 'coba', 'coba', 'coba', NULL, NULL, '123456789.00', 10, 10, 10, 'disetujui', 0, NULL, 'coba', NULL, NULL, NULL, NULL, '2026-06-03 06:31:49', '2026-06-03 06:38:49');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `admin_email_unique` (`email`);

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`),
  ADD UNIQUE KEY `customer_email_unique` (`email`);

--
-- Indeks untuk tabel `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `detail_pemesanan_id_pemesanan_foreign` (`id_pemesanan`);

--
-- Indeks untuk tabel `dokumen_villa`
--
ALTER TABLE `dokumen_villa`
  ADD PRIMARY KEY (`id_dokumen_villa`),
  ADD KEY `dokumen_villa_id_villa_foreign` (`id_villa`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `fasilitas_villa`
--
ALTER TABLE `fasilitas_villa`
  ADD PRIMARY KEY (`id_fasilitas`),
  ADD KEY `fasilitas_villa_id_villa_foreign` (`id_villa`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id_owner`),
  ADD UNIQUE KEY `owner_email_unique` (`email`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `pemesanan_id_villa_foreign` (`id_villa`),
  ADD KEY `pemesanan_id_customer_foreign` (`id_customer`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD UNIQUE KEY `ulasan_id_villa_id_customer_unique` (`id_villa`,`id_customer`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `villa`
--
ALTER TABLE `villa`
  ADD PRIMARY KEY (`id_villa`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  MODIFY `id_detail` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `dokumen_villa`
--
ALTER TABLE `dokumen_villa`
  MODIFY `id_dokumen_villa` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `fasilitas_villa`
--
ALTER TABLE `fasilitas_villa`
  MODIFY `id_fasilitas` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `owner`
--
ALTER TABLE `owner`
  MODIFY `id_owner` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `villa`
--
ALTER TABLE `villa`
  MODIFY `id_villa` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD CONSTRAINT `detail_pemesanan_id_pemesanan_foreign` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dokumen_villa`
--
ALTER TABLE `dokumen_villa`
  ADD CONSTRAINT `dokumen_villa_id_villa_foreign` FOREIGN KEY (`id_villa`) REFERENCES `villa` (`id_villa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `fasilitas_villa`
--
ALTER TABLE `fasilitas_villa`
  ADD CONSTRAINT `fasilitas_villa_id_villa_foreign` FOREIGN KEY (`id_villa`) REFERENCES `villa` (`id_villa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_id_customer_foreign` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_id_villa_foreign` FOREIGN KEY (`id_villa`) REFERENCES `villa` (`id_villa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_id_villa_foreign` FOREIGN KEY (`id_villa`) REFERENCES `villa` (`id_villa`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
