/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `bank` (
  `id_bank` int NOT NULL AUTO_INCREMENT,
  `nama_bank` varchar(50) NOT NULL DEFAULT '0',
  `keterangan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '-',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bank`),
  UNIQUE KEY `Index 2` (`nama_bank`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `bank` DISABLE KEYS */;
INSERT INTO `bank` (`id_bank`, `nama_bank`, `keterangan`, `created_at`, `updated_at`) VALUES
	(1, 'BCA', '-', '2022-10-31 12:01:26', '2022-10-31 12:01:48'),
	(2, 'MANDIRI', '-', '2022-10-31 12:01:45', '2022-10-31 12:01:45'),
	(3, 'BNI', '-', '2022-10-31 12:01:51', '2022-10-31 12:01:51'),
	(4, 'BRI', '-', '2022-10-31 12:01:55', '2022-10-31 12:01:55');
/*!40000 ALTER TABLE `bank` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `rekening_admin` (
  `id_rekening` int NOT NULL AUTO_INCREMENT,
  `id_bank` int NOT NULL DEFAULT '0',
  `no_rekening` varchar(50) DEFAULT NULL,
  `pemilik_rekening` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_rekening`),
  UNIQUE KEY `Index 2` (`id_bank`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `rekening_admin` DISABLE KEYS */;
INSERT INTO `rekening_admin` (`id_rekening`, `id_bank`, `no_rekening`, `pemilik_rekening`, `created_at`, `updated_at`) VALUES
	(1, 1, '123456', 'BosCOD', '2022-10-31 12:02:24', '2022-10-31 14:33:21'),
	(2, 2, '567890', 'BosCOD', '2022-10-31 12:02:44', '2022-10-31 14:33:23'),
	(3, 3, '654321', 'BosCOD', '2022-10-31 12:02:53', '2022-10-31 14:33:23'),
	(4, 4, '424124', 'BosCOD', '2022-10-31 12:04:27', '2022-10-31 14:33:24');
/*!40000 ALTER TABLE `rekening_admin` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `transaksi_transfer` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_user` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nilai_transfer` int NOT NULL DEFAULT '0',
  `kode_unik` int NOT NULL DEFAULT '0',
  `biaya_admin` int NOT NULL DEFAULT '0',
  `bank_pengirim` int NOT NULL,
  `bank_tujuan` int NOT NULL,
  `rekening_tujuan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `atas_nama_tujuan` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'PENDING',
  `masa_berlaku` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaksi`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*!40000 ALTER TABLE `transaksi_transfer` DISABLE KEYS */;
INSERT INTO `transaksi_transfer` (`id_transaksi`, `no_transaksi`, `id_user`, `nilai_transfer`, `kode_unik`, `biaya_admin`, `bank_pengirim`, `bank_tujuan`, `rekening_tujuan`, `atas_nama_tujuan`, `status`, `masa_berlaku`, `created_at`, `updated_at`) VALUES
	(1, 'TF2022110200001', '1', 10000, 749, 0, 3, 1, '654321', 'Microlax', 'PENDING', '2022-11-04 05:34:14', '2022-11-02 05:34:14', '2022-11-02 05:34:14');
/*!40000 ALTER TABLE `transaksi_transfer` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'nadhiras', 'nadhiras42@gmail.com', NULL, '$2y$10$EpfoDs16fIjGkK/ivVbKt.NqdU0I5K5M0B7J7j908FxCdsdDGnPea', NULL, '2022-10-31 04:08:28', '2022-10-31 04:08:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
