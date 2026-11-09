-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2026 at 06:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ukk_2026`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_guru`
--

CREATE TABLE `t_guru` (
  `id` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `update_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_guru`
--

INSERT INTO `t_guru` (`id`, `nip`, `nama`, `email`, `status_aktif`, `user_id`, `created_at`, `update_at`) VALUES
(1, '1980100001', 'Wawan Kurniawan, S.Pd.I', 'guru01@sekolah.test', 1, 1, '2026-11-04 04:28:28', '2026-11-04 04:28:28'),
(2, '1980200002', 'Ai Nuryidah, S.Pd', 'guru02@sekolah.test', 1, 2, '2026-11-04 04:28:28', '2026-11-04 04:28:28'),
(3, '1980300003', 'Maya Permata Endah, S.Pd', 'guru03@sekolah.test', 1, 3, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(4, '1980400004', 'Dody Jaenudin, S.Pd.', 'guru04@sekolah.test', 1, 4, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(5, '1980500005', 'Dedi Kardiman, S.Pd', 'guru05@sekolah.test', 1, 5, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(6, '1980600006', 'Uca Musa Yusenda, S.Pd', 'guru06@sekolah.test', 1, 6, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(7, '1980700007', 'Drs. Ahmad Nur Mukhlis', 'guru07@sekolah.test', 1, 7, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(8, '1980800008', 'Drs. Heri', 'guru08@sekolah.test', 1, 8, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(9, '1980900009', 'Asep Bastaman', 'guru09@sekolah.test', 1, 9, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(10, '1981000010', 'H. Encep Maemun, S.T., M.Pd', 'guru10@sekolah.test', 1, 10, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(11, '1981100011', 'Opik', 'guru11@sekolah.test', 1, 11, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(12, '1981200012', 'Ida Farida, S.Pd', 'guru12@sekolah.test', 1, 12, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(13, '1981300013', 'Idah Mahmudah, S.Pd', 'guru13@sekolah.test', 1, 13, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(14, '1981400014', 'Drs. Muhamad Ali', 'guru14@sekolah.test', 1, 14, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(15, '1981500015', 'Helmi Ali Akbar, S.Pd.I', 'guru15@sekolah.test', 1, 15, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(16, '1981600016', 'Syeni Suswarini, S.Pd', 'guru16@sekolah.test', 1, 16, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(17, '1981700017', 'Wiwid Yulianti, S.Pd', 'guru17@sekolah.test', 1, 17, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(18, '1981800018', 'Ansor Muhajir, S.T', 'guru18@sekolah.test', 1, 18, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(19, '1981900019', 'Ogi Gaosun Jajang, S.Pd.I', 'guru19@sekolah.test', 1, 19, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(20, '1982000020', 'Sandi Mulyana, S.Pd', 'guru20@sekolah.test', 1, 20, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(21, '1982100021', 'Erna Suryani, S.Pd', 'guru21@sekolah.test', 1, 21, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(22, '1982200022', 'Ai Wahidah, S.Ag', 'guru22@sekolah.test', 1, 22, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(23, '1982300023', 'Chandra Aditya Yusup, S.Pd', 'guru23@sekolah.test', 1, 23, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(24, '1982400024', 'Arif Kusmawan, S.Pd', 'guru24@sekolah.test', 1, 24, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(25, '1982500025', 'Asep Taufiq Hidayat, S.Kom', 'guru25@sekolah.test', 1, 25, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(26, '1982600026', 'Rendy Ichsan Kusumah, S.T', 'guru26@sekolah.test', 1, 26, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(27, '1982700027', 'Feri Rahmawan, S.Pd', 'guru27@sekolah.test', 1, 27, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(28, '1982800028', 'Ari Sandriana, S.Kom', 'guru28@sekolah.test', 1, 28, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(29, '1982900029', 'Yaneu Nurdian, S.Pd., M.Pd', 'guru29@sekolah.test', 1, 29, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, '1983000030', 'Ucu Muhammad Khoer N, S.Pd', 'guru30@sekolah.test', 1, 30, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(31, '1983100031', 'Mira Dwi Rahmawati, S.Ag', 'guru31@sekolah.test', 1, 31, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(32, '1983200032', 'Aldi Maulana, S.Pd', 'guru32@sekolah.test', 1, 32, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(33, '1983300033', 'Eli Rosliah, S.Pd', 'guru33@sekolah.test', 1, 33, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(34, '1983400034', 'Isni Nur Fitriani, S.Pd', 'guru34@sekolah.test', 1, 34, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(35, '1983500035', 'Aanisah Putri Nur Aini, S.Pd', 'guru35@sekolah.test', 1, 35, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(36, '1983600036', 'Yusi Luthfiani, S.Sos.I', 'guru36@sekolah.test', 1, 36, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(37, '1983700037', 'Lilis Rustandy Mawardi, S.Pd., M.Pd', 'guru37@sekolah.test', 1, 37, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(38, '1983800038', 'Yusrin Noer Sabrina, S.Pd', 'guru38@sekolah.test', 1, 38, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(39, '1983900039', 'Senny Indayani, S.Pd', 'guru39@sekolah.test', 1, 39, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(40, '1984000040', 'Ilma Amalia, S.Pd', 'guru40@sekolah.test', 1, 40, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(41, '1984100041', 'Alvira Sofwatunnisa, S.Pd', 'guru41@sekolah.test', 1, 41, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(42, '1984200042', 'Risna Andriyani, S.Pd', 'guru42@sekolah.test', 1, 42, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(43, '1984300043', 'Dra. Tintin Noorhayati', 'guru43@sekolah.test', 1, 43, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(44, '1984400044', 'Rizkia Fahira, S.Pd', 'guru44@sekolah.test', 1, 44, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(45, '1984500045', 'Frasetio', 'guru45@sekolah.test', 1, 45, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(46, '1984600046', 'Ratnaningsih Hatil Aeni, S.Pd', 'guru46@sekolah.test', 1, 46, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(47, '1984700047', 'Dheny Darajatul Hasani, S.Pd', 'guru47@sekolah.test', 1, 47, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(48, '1984800048', 'Fahrullah, S.Pd', 'guru48@sekolah.test', 1, 48, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(49, '1984900049', 'Aan Andriana, S.T', 'guru49@sekolah.test', 1, 49, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(50, '1985000050', 'Fitri Fauziah, S.T', 'guru50@sekolah.test', 1, 50, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(57, 'TEMP-WK-51', 'Riesy Putri Navia, S.Kom', 'user51@sekolah.test', 1, 61, '2026-11-09 03:50:53', '2026-11-09 03:50:53'),
(58, 'TEMP-WK-52', 'Ai Nuryidah, S.Pd', 'user52@sekolah.test', 1, 62, '2026-11-09 03:50:53', '2026-11-09 03:50:53'),
(59, 'TEMP-WK-53', 'Irvan Rizal, S.Pd', 'user53@sekolah.test', 1, 63, '2026-11-09 03:50:53', '2026-11-09 03:50:53'),
(60, 'TEMP-WK-54', 'Nida Aulia, S.Pd', 'user54@sekolah.test', 1, 64, '2026-11-09 03:50:53', '2026-11-09 03:50:53'),
(61, 'TEMP-WK-55', 'Sri Nurrizki Putri Krisnadi, S.Pd', 'user55@sekolah.test', 1, 65, '2026-11-09 03:50:54', '2026-11-09 03:50:54'),
(62, 'TEMP-WK-56', 'Rurry Febriani Maryadi, S.Pd', 'user56@sekolah.test', 1, 66, '2026-11-09 03:50:54', '2026-11-09 03:50:54');

-- --------------------------------------------------------

--
-- Table structure for table `t_kelas`
--

CREATE TABLE `t_kelas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tingkat` varchar(20) NOT NULL,
  `jurusan` varchar(100) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_kelas`
--

INSERT INTO `t_kelas` (`id`, `nama`, `tingkat`, `jurusan`, `status_aktif`, `created_at`, `update_at`) VALUES
(1, 'XII RPL 1', '12', 'RPL', 0, '2026-11-05 01:52:27', '2026-11-05 02:28:35'),
(2, 'XII RPL 2', '12', 'RPL', 1, '2026-11-05 01:52:27', '2026-11-05 02:28:35'),
(3, 'XII TKJ 1', '12', 'TKJ', 0, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(4, 'X RPL 1', '10', 'RPL', 0, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(5, 'XI TSM 1', '11', 'TSM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(6, 'XII RPL 3', '12', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(7, 'X TKJ 1', '10', 'TKJ', 0, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(8, 'XI TKR 1', '11', 'TKR', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(9, 'XII TKR 1', '12', 'TKR', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(10, 'X TSM 1', '10', 'TSM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(11, 'XI RPL 1', '11', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(12, 'XII TKJ 2', '12', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(13, 'X TKR 1', '10', 'TKR', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(14, 'XI BDPM 1', '11', 'BDPM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(15, 'XII BDPM 1', '12', 'BDPM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(16, 'X RPL 2', '10', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(17, 'XI TKJ 1', '11', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(18, 'XII TSM 1', '12', 'TSM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(19, 'X TSM 2', '10', 'TSM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(20, 'XI TSM 2', '11', 'TSM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(21, 'XII RPL 4', '12', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(22, 'X TKJ 2', '10', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(23, 'XI TKR 2', '11', 'TKR', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(24, 'XII TKR 2', '12', 'TKR', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(25, 'X BDPM 1', '10', 'BDPM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(26, 'XI RPL 2', '11', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(27, 'XII TKJ 3', '12', 'TKJ', 0, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(28, 'X TKJ 3', '10', 'TKJ', 0, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(29, 'XI RPL 3', '11', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(30, 'XII TSM 2', '12', 'TSM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(31, 'X RPL 3', '10', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(32, 'XI TKJ 2', '11', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(33, 'XII TKJ 4', '12', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(34, 'X RPL 4', '10', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(35, 'XI TSM 3', '11', 'TSM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(36, 'XII RPL 5', '12', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(37, 'X TKJ 4', '10', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(38, 'XI TKJ 3', '11', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(39, 'XII RPL 6', '12', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(40, 'X TKR 2', '10', 'TKR', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(41, 'XI RPL 4', '11', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(42, 'XII TKJ 5', '12', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(43, 'X TKJ 5', '10', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(44, 'XI RPL 5', '11', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(45, 'XII TSM 3', '12', 'TSM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(46, 'X RPL 5', '10', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(47, 'XI TKJ 4', '11', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(48, 'XII TKJ 6', '12', 'TKJ', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(49, 'X RPL 6', '10', 'RPL', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(50, 'XI TSM 4', '11', 'TSM', 1, '2026-01-01 01:00:00', '2026-11-05 02:28:35'),
(54, 'X TKJ 6', '10', 'TKJ', 1, '2026-11-03 04:32:21', '2026-11-05 02:28:35'),
(60, 'XI RPL 6', '11', 'RPL', 1, '2026-11-05 01:36:35', '2026-11-05 02:28:35'),
(61, 'XI TKJ 5', '11', 'TKJ', 1, '2026-11-05 01:36:35', '2026-11-05 02:28:35'),
(62, 'X TKR 3', '10', 'TKR', 1, '2026-11-09 03:14:53', '2026-11-09 03:14:53'),
(63, 'X TKR 4', '10', 'TKR', 1, '2026-11-09 03:14:53', '2026-11-09 03:14:53'),
(64, 'X TKR 5', '10', 'TKR', 1, '2026-11-09 03:14:53', '2026-11-09 03:14:53'),
(65, 'XII TKR 4', '12', 'TKR', 1, '2026-11-09 03:14:53', '2026-11-09 03:14:53'),
(66, 'XII TKR 5', '12', 'TKR', 1, '2026-11-09 03:14:53', '2026-11-09 03:14:53'),
(67, 'X TSM 3', '10', 'TSM', 1, '2026-11-09 03:14:53', '2026-11-09 03:14:53'),
(68, 'XI TKR 3', '11', 'TKR', 1, '2026-11-09 03:14:53', '2026-11-09 03:14:53'),
(69, 'XII TKR 3', 'XII', 'TKR', 1, '2026-11-09 03:45:31', '2026-11-09 03:45:31');

-- --------------------------------------------------------

--
-- Table structure for table `t_kelas_siswa`
--

CREATE TABLE `t_kelas_siswa` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_kelas_siswa`
--

INSERT INTO `t_kelas_siswa` (`id`, `siswa_id`, `tahun_ajaran_id`, `kelas_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `update_at`) VALUES
(62, 1, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(63, 2, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(64, 3, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(65, 4, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(66, 5, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(67, 6, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(68, 7, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(69, 8, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(70, 9, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(71, 10, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(72, 11, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(73, 12, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(74, 13, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(75, 14, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(76, 15, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(77, 16, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(78, 17, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(79, 18, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(80, 19, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(81, 20, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(82, 21, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(83, 22, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(84, 23, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(85, 24, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(86, 25, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56'),
(87, 26, 26, 2, '0000-00-00', '0000-00-00', 0, '2026-11-09 04:31:56', '2026-11-09 04:31:56');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran`
--

CREATE TABLE `t_pelanggaran` (
  `id` int(11) NOT NULL,
  `pelanggaran_kategori_id` int(11) NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `poin` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_pelanggaran`
--

INSERT INTO `t_pelanggaran` (`id`, `pelanggaran_kategori_id`, `kode`, `nama`, `poin`, `deskripsi`, `status_aktif`, `created_at`, `update_at`) VALUES
(1, 1, 'PLG-001', 'Terlambat masuk sekolah 1', 2, 'Deskripsi untuk terlambat masuk sekolah 1', 1, '2026-11-05 01:58:42', '2026-11-05 01:58:42'),
(2, 2, 'PLG-002', 'Tidak memakai atribut lengkap', 1, 'Deskripsi untuk tidak memakai atribut lengkap 1', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(3, 3, 'PLG-003', 'Tidak mengerjakan tugas 1', 4, 'Deskripsi untuk tidak mengerjakan tugas 1', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(4, 4, 'PLG-004', 'Tidak hadir tanpa keterangan 1', 5, 'Deskripsi untuk tidak hadir tanpa keterangan 1', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(5, 5, 'PLG-005', 'Membuang sampah sembarangan 1', 6, 'Deskripsi untuk membuang sampah sembarangan 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(6, 6, 'PLG-006', 'Ribut di kelas 1', 7, 'Deskripsi untuk ribut di kelas 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(7, 7, 'PLG-007', 'Seragam tidak sesuai 1', 8, 'Deskripsi untuk seragam tidak sesuai 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(8, 8, 'PLG-008', 'Keluar kelas tanpa izin 1', 9, 'Deskripsi untuk keluar kelas tanpa izin 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(9, 9, 'PLG-009', 'Berkata tidak sopan 1', 10, 'Deskripsi untuk berkata tidak sopan 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(10, 10, 'PLG-010', 'Tidak membawa perlengkapan 1', 1, 'Deskripsi untuk tidak membawa perlengkapan 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(11, 11, 'PLG-011', 'Terlambat masuk sekolah 2', 2, 'Deskripsi untuk terlambat masuk sekolah 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(12, 12, 'PLG-012', 'Tidak memakai atribut lengkap ', 3, 'Deskripsi untuk tidak memakai atribut lengkap 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(13, 13, 'PLG-013', 'Tidak mengerjakan tugas 2', 4, 'Deskripsi untuk tidak mengerjakan tugas 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(14, 14, 'PLG-014', 'Tidak hadir tanpa keterangan 2', 5, 'Deskripsi untuk tidak hadir tanpa keterangan 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(15, 15, 'PLG-015', 'Membuang sampah sembarangan 2', 6, 'Deskripsi untuk membuang sampah sembarangan 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(16, 16, 'PLG-016', 'Ribut di kelas 2', 7, 'Deskripsi untuk ribut di kelas 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(17, 17, 'PLG-017', 'Seragam tidak sesuai 2', 8, 'Deskripsi untuk seragam tidak sesuai 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(18, 18, 'PLG-018', 'Keluar kelas tanpa izin 2', 9, 'Deskripsi untuk keluar kelas tanpa izin 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(19, 19, 'PLG-019', 'Berkata tidak sopan 2', 10, 'Deskripsi untuk berkata tidak sopan 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(20, 20, 'PLG-020', 'Tidak membawa perlengkapan 2', 1, 'Deskripsi untuk tidak membawa perlengkapan 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(21, 21, 'PLG-021', 'Terlambat masuk sekolah 3', 2, 'Deskripsi untuk terlambat masuk sekolah 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(22, 22, 'PLG-022', 'Tidak memakai atribut lengkap ', 3, 'Deskripsi untuk tidak memakai atribut lengkap 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(23, 23, 'PLG-023', 'Tidak mengerjakan tugas 3', 4, 'Deskripsi untuk tidak mengerjakan tugas 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(24, 24, 'PLG-024', 'Tidak hadir tanpa keterangan 3', 5, 'Deskripsi untuk tidak hadir tanpa keterangan 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(25, 25, 'PLG-025', 'Membuang sampah sembarangan 3', 6, 'Deskripsi untuk membuang sampah sembarangan 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(26, 26, 'PLG-026', 'Ribut di kelas 3', 7, 'Deskripsi untuk ribut di kelas 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(27, 27, 'PLG-027', 'Seragam tidak sesuai 3', 8, 'Deskripsi untuk seragam tidak sesuai 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(28, 28, 'PLG-028', 'Keluar kelas tanpa izin 3', 9, 'Deskripsi untuk keluar kelas tanpa izin 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(29, 29, 'PLG-029', 'Berkata tidak sopan 3', 10, 'Deskripsi untuk berkata tidak sopan 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, 30, 'PLG-030', 'Tidak membawa perlengkapan 3', 1, 'Deskripsi untuk tidak membawa perlengkapan 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(31, 31, 'PLG-031', 'Terlambat masuk sekolah 4', 2, 'Deskripsi untuk terlambat masuk sekolah 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(32, 32, 'PLG-032', 'Tidak memakai atribut lengkap ', 3, 'Deskripsi untuk tidak memakai atribut lengkap 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(33, 33, 'PLG-033', 'Tidak mengerjakan tugas 4', 4, 'Deskripsi untuk tidak mengerjakan tugas 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(34, 34, 'PLG-034', 'Tidak hadir tanpa keterangan 4', 5, 'Deskripsi untuk tidak hadir tanpa keterangan 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(35, 35, 'PLG-035', 'Membuang sampah sembarangan 4', 6, 'Deskripsi untuk membuang sampah sembarangan 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(36, 36, 'PLG-036', 'Ribut di kelas 4', 7, 'Deskripsi untuk ribut di kelas 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(37, 37, 'PLG-037', 'Seragam tidak sesuai 4', 8, 'Deskripsi untuk seragam tidak sesuai 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(38, 38, 'PLG-038', 'Keluar kelas tanpa izin 4', 9, 'Deskripsi untuk keluar kelas tanpa izin 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(39, 39, 'PLG-039', 'Berkata tidak sopan 4', 10, 'Deskripsi untuk berkata tidak sopan 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(40, 40, 'PLG-040', 'Tidak membawa perlengkapan 4', 1, 'Deskripsi untuk tidak membawa perlengkapan 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(41, 41, 'PLG-041', 'Terlambat masuk sekolah 5', 2, 'Deskripsi untuk terlambat masuk sekolah 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(42, 42, 'PLG-042', 'Tidak memakai atribut lengkap ', 3, 'Deskripsi untuk tidak memakai atribut lengkap 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(43, 43, 'PLG-043', 'Tidak mengerjakan tugas 5', 4, 'Deskripsi untuk tidak mengerjakan tugas 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(44, 44, 'PLG-044', 'Tidak hadir tanpa keterangan 5', 5, 'Deskripsi untuk tidak hadir tanpa keterangan 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(45, 45, 'PLG-045', 'Membuang sampah sembarangan 5', 677, 'Deskripsi untuk membuang sampah sembarangan 5', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(46, 46, 'PLG-046', 'Ribut di kelas 5', 7, 'Deskripsi untuk ribut di kelas 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(47, 47, 'PLG-047', 'Seragam tidak sesuai 5', 8, 'Deskripsi untuk seragam tidak sesuai 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(48, 48, 'PLG-048', 'Keluar kelas tanpa izin 5', 9, 'Deskripsi untuk keluar kelas tanpa izin 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(49, 49, 'PLG-049', 'Berkata tidak sopan 5', 10, 'Deskripsi untuk berkata tidak sopan 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(50, 50, 'PLG-050', 'Tidak membawa perlengkapan 5', 1, 'Deskripsi untuk tidak membawa perlengkapan 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(53, 10, '45435', 'Terlambat Datang', 55, 'etgdgrtg', 0, '2026-11-04 02:02:35', '2026-11-04 02:02:35'),
(54, 20, '65457', 'Terlambat Datang', 6, 'hjhvgjghjvhg', 0, '2026-11-04 02:03:02', '2026-11-04 02:03:02'),
(55, 30, '678888', 'Membolos', 777, 'vxfsgfsgfsrfdrsfg', 0, '2026-11-04 02:04:20', '2026-11-04 02:04:20'),
(56, 10, 'PLG-005', 'Membolos', 777, 'Bolos saat jam pelajaran', 0, '2026-11-04 02:07:34', '2026-11-04 02:07:34'),
(57, 20, 'PLG-005', 'Membolos', 934859034, 'retrt', 0, '2026-11-04 02:11:36', '2026-11-04 02:11:36');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran_kategori`
--

CREATE TABLE `t_pelanggaran_kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_pelanggaran_kategori`
--

INSERT INTO `t_pelanggaran_kategori` (`id`, `nama`, `deskripsi`, `status_aktif`, `created_at`, `update_at`) VALUES
(1, 'Kedisiplinan 1', 'Kategori pelanggaran kedisiplinan 1', 1, '2026-11-05 01:58:08', '2026-11-05 01:58:08'),
(2, 'Kerapian 1', 'Kategori pelanggaran kerapian 1', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(3, 'Kehadiran 1', 'Kategori pelanggaran kehadiran 1', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(4, 'Sikap 1', 'Kategori pelanggaran sikap 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(5, 'Kebersihan 1', 'Kategori pelanggaran kebersihan 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(6, 'Ketertiban 1', 'Kategori pelanggaran ketertiban 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(7, 'Seragam 1', 'Kategori pelanggaran seragam 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(8, 'Tugas 1', 'Kategori pelanggaran tugas 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(9, 'Perilaku 1', 'Kategori pelanggaran perilaku 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(10, 'Akademik 1', 'Kategori pelanggaran akademik 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(11, 'Kedisiplinan 2', 'Kategori pelanggaran kedisiplinan 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(12, 'Kerapian 2', 'Kategori pelanggaran kerapian 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(13, 'Kehadiran 2', 'Kategori pelanggaran kehadiran 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(14, 'Sikap 2', 'Kategori pelanggaran sikap 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(15, 'Kebersihan 2', 'Kategori pelanggaran kebersihan 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(16, 'Ketertiban 2', 'Kategori pelanggaran ketertiban 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(17, 'Seragam 2', 'Kategori pelanggaran seragam 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(18, 'Tugas 2', 'Kategori pelanggaran tugas 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(19, 'Perilaku 2', 'Kategori pelanggaran perilaku 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(20, 'Akademik 2', 'Kategori pelanggaran akademik 2', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(21, 'Kedisiplinan 3', 'Kategori pelanggaran kedisiplinan 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(22, 'Kerapian 3', 'Kategori pelanggaran kerapian 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(23, 'Kehadiran 3', 'Kategori pelanggaran kehadiran 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(24, 'Sikap 3', 'Kategori pelanggaran sikap 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(25, 'Kebersihan 3', 'Kategori pelanggaran kebersihan 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(26, 'Ketertiban 3', 'Kategori pelanggaran ketertiban 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(27, 'Seragam 3', 'Kategori pelanggaran seragam 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(28, 'Tugas 3', 'Kategori pelanggaran tugas 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(29, 'Perilaku 3', 'Kategori pelanggaran perilaku 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, 'Akademik 3', 'Kategori pelanggaran akademik 3', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(31, 'Kedisiplinan 4', 'Kategori pelanggaran kedisiplinan 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(32, 'Kerapian 4', 'Kategori pelanggaran kerapian 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(33, 'Kehadiran 4', 'Kategori pelanggaran kehadiran 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(34, 'Sikap 4', 'Kategori pelanggaran sikap 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(35, 'Kebersihan 4', 'Kategori pelanggaran kebersihan 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(36, 'Ketertiban 4', 'Kategori pelanggaran ketertiban 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(37, 'Seragam 4', 'Kategori pelanggaran seragam 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(38, 'Tugas 4', 'Kategori pelanggaran tugas 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(39, 'Perilaku 4', 'Kategori pelanggaran perilaku 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(40, 'Akademik 4', 'Kategori pelanggaran akademik 4', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(41, 'Kedisiplinan 5', 'Kategori pelanggaran kedisiplinan 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(42, 'Kerapian 5', 'Kategori pelanggaran kerapian 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(43, 'Kehadiran 5', 'Kategori pelanggaran kehadiran 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(44, 'Sikap 5', 'Kategori pelanggaran sikap 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(45, 'Kebersihan 5', 'Kategori pelanggaran kebersihan 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(46, 'Ketertiban 5', 'Kategori pelanggaran ketertiban 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(47, 'Seragam 5', 'Kategori pelanggaran seragam 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(48, 'Tugas 5', 'Kategori pelanggaran tugas 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(49, 'Perilaku 5', 'Kategori pelanggaran perilaku 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(50, 'Akademik 5', 'Kategori pelanggaran akademik 5', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(52, 'fdgtg', 'rtgrtdgrdgrg2342', 0, '2026-11-04 01:59:14', '2026-11-04 01:59:20');

-- --------------------------------------------------------

--
-- Table structure for table `t_pelanggaran_siswa`
--

CREATE TABLE `t_pelanggaran_siswa` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `nama_siswa` varchar(150) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `pelanggaran_id` int(11) NOT NULL,
  `nama_pelanggaran` varchar(150) NOT NULL,
  `pelanggaran_kategori_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `nama_guru` varchar(150) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text NOT NULL,
  `poin` int(11) NOT NULL,
  `tindakan` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_pelanggaran_siswa`
--

INSERT INTO `t_pelanggaran_siswa` (`id`, `tahun_ajaran_id`, `siswa_id`, `nama_siswa`, `kelas_id`, `nama_kelas`, `pelanggaran_id`, `nama_pelanggaran`, `pelanggaran_kategori_id`, `guru_id`, `nama_guru`, `tanggal`, `keterangan`, `poin`, `tindakan`, `status`, `created_at`, `update_at`) VALUES
(1, 26, 1, 'ADITTIA NUR LANTIP', 1, 'XII RPL 1', 1, 'Terlambat masuk sekolah 1', 1, 1, 'Wawan Kurniawan, S.Pd.I', '2026-08-01', 'Catatan pelanggaran siswa ke-1', 2, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(2, 26, 2, 'AGNIA ROSYA NOERILLAH', 2, 'XII RPL 2', 2, 'Tidak memakai atribut lengkap', 2, 2, 'Ai Nuryidah, S.Pd', '2026-08-02', 'Catatan pelanggaran siswa ke-2', 1, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(3, 26, 3, 'ANISA AMIRULLA', 3, 'XII TKJ 1', 3, 'Tidak mengerjakan tugas 1', 3, 3, 'Guru 03', '2026-08-03', 'Catatan pelanggaran siswa ke-3', 4, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(4, 26, 4, 'ARADEA KANDI', 4, 'X RPL 1', 4, 'Tidak hadir tanpa keterangan 1', 4, 4, 'Guru 04', '2026-08-04', 'Catatan pelanggaran siswa ke-4', 5, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(5, 26, 5, 'ARFA DIESA OKMALELIZA', 5, 'XI TSM 1', 5, 'Membuang sampah sembarangan 1', 5, 5, 'Guru 05', '2026-08-05', 'Catatan pelanggaran siswa ke-5', 6, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(6, 26, 6, 'FITRI NUR SYIAMI', 6, 'XII RPL 3', 6, 'Ribut di kelas 1', 6, 6, 'Guru 06', '2026-08-06', 'Catatan pelanggaran siswa ke-6', 7, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(7, 26, 7, 'JUNITA PUTRI NUR RAHMAN', 7, 'X TKJ 1', 7, 'Seragam tidak sesuai 1', 7, 7, 'Guru 07', '2026-08-07', 'Catatan pelanggaran siswa ke-7', 8, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(8, 26, 8, 'MOHAMMAD ATALARIK ALI SYAHBANA', 8, 'XI TKR 1', 8, 'Keluar kelas tanpa izin 1', 8, 8, 'Guru 08', '2026-08-08', 'Catatan pelanggaran siswa ke-8', 9, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(9, 26, 9, 'MUHAMMAD FAHRIL FAHRUROZI', 9, 'XII TKR 1', 9, 'Berkata tidak sopan 1', 9, 9, 'Guru 09', '2026-08-09', 'Catatan pelanggaran siswa ke-9', 10, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(10, 26, 10, 'MUHAMMAD ADNAN SYAEFUL ISLAMI', 10, 'X TSM 1', 10, 'Tidak membawa perlengkapan 1', 10, 10, 'Guru 10', '2026-08-10', 'Catatan pelanggaran siswa ke-10', 1, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(11, 26, 11, 'MUHAMMAD ZAHRU AQILA FIKRI', 11, 'XI RPL 1', 11, 'Terlambat masuk sekolah 2', 11, 11, 'Guru 11', '2026-08-11', 'Catatan pelanggaran siswa ke-11', 2, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(12, 26, 12, 'NADIA ZULFA SORAYA', 12, 'XII TKJ 2', 12, 'Tidak memakai atribut lengkap 2', 12, 12, 'Guru 12', '2026-08-12', 'Catatan pelanggaran siswa ke-12', 3, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(13, 26, 13, 'NAZRIL ILHAM', 13, 'X TKR 1', 13, 'Tidak mengerjakan tugas 2', 13, 13, 'Guru 13', '2026-08-13', 'Catatan pelanggaran siswa ke-13', 4, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(14, 26, 14, 'PUSPITA DEWI', 14, 'XI BDPM 1', 14, 'Tidak hadir tanpa keterangan 2', 14, 14, 'Guru 14', '2026-08-14', 'Catatan pelanggaran siswa ke-14', 5, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(15, 26, 15, 'RAKHA MUHAMMAD HAYKAL', 15, 'XII BDPM 1', 15, 'Membuang sampah sembarangan 2', 15, 15, 'Guru 15', '2026-08-15', 'Catatan pelanggaran siswa ke-15', 6, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(16, 26, 16, 'RAMI MELANI', 16, 'X RPL 2', 16, 'Ribut di kelas 2', 16, 16, 'Guru 16', '2026-08-16', 'Catatan pelanggaran siswa ke-16', 7, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(17, 26, 17, 'REIHAN MUZAKI', 17, 'XI TKJ 1', 17, 'Seragam tidak sesuai 2', 17, 17, 'Guru 17', '2026-08-17', 'Catatan pelanggaran siswa ke-17', 8, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(18, 26, 18, 'RESA JULIANSAH', 18, 'XII TSM 1', 18, 'Keluar kelas tanpa izin 2', 18, 18, 'Guru 18', '2026-08-18', 'Catatan pelanggaran siswa ke-18', 9, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(19, 26, 19, 'RIZKI PERMATA SUGIANTO', 19, 'X TSM 2', 19, 'Berkata tidak sopan 2', 19, 19, 'Guru 19', '2026-08-19', 'Catatan pelanggaran siswa ke-19', 10, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(20, 26, 20, 'SAHRA FIRAJ', 20, 'XI TSM 2', 20, 'Tidak membawa perlengkapan 2', 20, 20, 'Guru 20', '2026-08-20', 'Catatan pelanggaran siswa ke-20', 1, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(21, 26, 21, 'SENDI MARDIAN', 21, 'XII RPL 4', 21, 'Terlambat masuk sekolah 3', 21, 21, 'Guru 21', '2026-08-21', 'Catatan pelanggaran siswa ke-21', 2, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(22, 26, 22, 'SHAHAIDAN NIZZAR', 22, 'X TKJ 2', 22, 'Tidak memakai atribut lengkap 3', 22, 22, 'Guru 22', '2026-08-22', 'Catatan pelanggaran siswa ke-22', 3, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(23, 26, 23, 'SHERLY SATRIANI', 23, 'XI TKR 2', 23, 'Tidak mengerjakan tugas 3', 23, 23, 'Guru 23', '2026-08-23', 'Catatan pelanggaran siswa ke-23', 4, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(24, 26, 24, 'SOFI NURJANAH', 24, 'XII TKR 2', 24, 'Tidak hadir tanpa keterangan 3', 24, 24, 'Guru 24', '2026-08-24', 'Catatan pelanggaran siswa ke-24', 5, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(25, 26, 25, 'SURAYA SALSABILA AZZAHIRA', 25, 'X BDPM 1', 25, 'Membuang sampah sembarangan 3', 25, 25, 'Guru 25', '2026-08-25', 'Catatan pelanggaran siswa ke-25', 6, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-11-05 02:28:35'),
(26, 26, 26, 'TONI ANTONIO', 26, 'XI RPL 2', 26, 'Ribut di kelas 3', 26, 26, 'Guru 26', '2026-08-26', 'Catatan pelanggaran siswa ke-26', 7, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-11-05 02:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `t_siswa`
--

CREATE TABLE `t_siswa` (
  `id` int(11) NOT NULL,
  `nis` varchar(30) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `jenis_kelamin` char(1) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nis`, `nisn`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `status_aktif`, `created_at`, `update_at`) VALUES
(1, 'NIS20260001', '30000001', 'ADITTIA NUR LANTIP', 'L', '2008-07-04', 'Jl. Pendidikan No. 1, Tasikmalaya', 1, '2026-11-04 05:10:45', '2026-11-04 05:11:43'),
(2, 'NIS20260002', '30000002', 'AGNIA ROSYA NOERILLAH', 'P', '2008-07-26', 'Jl. Pendidikan No. 2, Tasikmalaya', 1, '2026-11-04 05:10:45', '2026-11-04 05:11:43'),
(3, 'NIS20260003', '30000003', 'ANISA AMIRULLA', 'L', '2011-04-10', 'Jl. Pendidikan No. 3, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(4, 'NIS20260004', '30000004', 'ARADEA KANDI', 'P', '2008-05-13', 'Jl. Pendidikan No. 4, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(5, 'NIS20260005', '30000005', 'ARFA DIESA OKMALELIZA', 'L', '2009-06-16', 'Jl. Pendidikan No. 5, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(6, 'NIS20260006', '30000006', 'FITRI NUR SYIAMI', 'P', '2010-07-19', 'Jl. Pendidikan No. 6, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(7, 'NIS20260007', '30000007', 'JUNITA PUTRI NUR RAHMAN', 'L', '2011-08-22', 'Jl. Pendidikan No. 7, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(8, 'NIS20260008', '30000008', 'MOHAMMAD ATALARIK ALI SYAHBANA', 'P', '2008-09-25', 'Jl. Pendidikan No. 8, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(9, 'NIS20260009', '30000009', 'MUHAMMAD FAHRIL FAHRUROZI', 'L', '2009-10-01', 'Jl. Pendidikan No. 9, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(10, 'NIS20260010', '30000010', 'MUHAMMAD ADNAN SYAEFUL ISLAMI', 'P', '2010-11-04', 'Jl. Pendidikan No. 10, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(11, 'NIS20260011', '30000011', 'MUHAMMAD ZAHRU AQILA FIKRI', 'L', '2011-12-07', 'Jl. Pendidikan No. 11, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(12, 'NIS20260012', '30000012', 'NADIA ZULFA SORAYA', 'P', '2008-01-10', 'Jl. Pendidikan No. 12, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(13, 'NIS20260013', '30000013', 'NAZRIL ILHAM', 'L', '2009-02-13', 'Jl. Pendidikan No. 13, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(14, 'NIS20260014', '30000014', 'PUSPITA DEWI', 'P', '2010-03-16', 'Jl. Pendidikan No. 14, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(15, 'NIS20260015', '30000015', 'RAKHA MUHAMMAD HAYKAL', 'L', '2011-04-19', 'Jl. Pendidikan No. 15, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(16, 'NIS20260016', '30000016', 'RANI MELANI', 'P', '2008-05-22', 'Jl. Pendidikan No. 16, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(17, 'NIS20260017', '30000017', 'REIHAN MUZAKI', 'L', '2009-06-25', 'Jl. Pendidikan No. 17, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(18, 'NIS20260018', '30000018', 'RESA JULIANSAH', 'P', '2010-07-01', 'Jl. Pendidikan No. 18, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(19, 'NIS20260019', '30000019', 'RIZKI PERMATA SUGIANTO', 'L', '2011-08-04', 'Jl. Pendidikan No. 19, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(20, 'NIS20260020', '30000020', 'SAHRA FIRAJ', 'P', '2008-09-07', 'Jl. Pendidikan No. 20, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(21, 'NIS20260021', '30000021', 'SENDI MARDIAN', 'L', '2009-10-10', 'Jl. Pendidikan No. 21, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(22, 'NIS20260022', '30000022', 'SHAHAIDAN NIZZAR', 'P', '2010-11-13', 'Jl. Pendidikan No. 22, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(23, 'NIS20260023', '30000023', 'SHERLY SATRIANI', 'L', '2011-12-16', 'Jl. Pendidikan No. 23, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(24, 'NIS20260024', '30000024', 'SOFI NURJANAH', 'P', '2008-01-19', 'Jl. Pendidikan No. 24, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(25, 'NIS20260025', '30000025', 'SURAYA SALSABILA AZZAHIRA', 'L', '2009-02-22', 'Jl. Pendidikan No. 25, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(26, 'NIS20260026', '30000026', 'TONI ANTONIO', 'P', '2010-03-25', 'Jl. Pendidikan No. 26, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `t_tahun_ajaran`
--

CREATE TABLE `t_tahun_ajaran` (
  `id` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_tahun_ajaran`
--

INSERT INTO `t_tahun_ajaran` (`id`, `nama`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `update_at`) VALUES
(4, '2004/2005', '2004-07-01', '2005-06-30', 0, '2026-01-01 01:00:00', '2026-11-04 00:38:46'),
(5, '2005/2006', '2005-07-01', '2006-06-30', 0, '2026-01-01 01:00:00', '2026-11-04 00:32:32'),
(6, '2006/2007', '2006-07-01', '2007-06-30', 0, '2026-01-01 01:00:00', '2026-11-04 00:32:55'),
(7, '2007/2008', '2007-07-01', '2008-06-30', 0, '2026-01-01 01:00:00', '2026-11-04 00:33:09'),
(8, '2008/2009', '2008-07-01', '2009-06-30', 0, '2026-01-01 01:00:00', '2026-11-04 00:33:21'),
(9, '2009/2010', '2009-07-01', '2010-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(10, '2010/2011', '2010-07-01', '2011-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(11, '2011/2012', '2011-07-01', '2012-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(12, '2012/2013', '2012-07-01', '2013-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(14, '2014/2015', '2014-07-01', '2015-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(15, '2015/2016', '2015-07-01', '2016-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(16, '2016/2017', '2016-07-01', '2017-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(17, '2017/2018', '2017-07-01', '2018-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(18, '2018/2019', '2018-07-01', '2019-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(19, '2019/2020', '2019-07-01', '2020-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(20, '2020/2021', '2020-07-01', '2021-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(21, '2021/2022', '2021-07-01', '2022-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(22, '2022/2023', '2022-07-01', '2023-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(23, '2023/2024', '2023-07-01', '2024-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(24, '2024/2025', '2024-07-01', '2025-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(25, '2025/2026', '2025-07-01', '2026-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(26, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(27, '2027/2028', '2027-07-01', '2028-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(28, '2028/2029', '2028-07-01', '2029-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(29, '2029/2030', '2029-07-01', '2030-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, '2030/2031', '2030-07-01', '2031-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(31, '2031/2032', '2031-07-01', '2032-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(32, '2032/2033', '2032-07-01', '2033-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(33, '2033/2034', '2033-07-01', '2034-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(34, '2034/2035', '2034-07-01', '2035-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(35, '2035/2036', '2035-07-01', '2036-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(36, '2036/2037', '2036-07-01', '2037-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(37, '2037/2038', '2037-07-01', '2038-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(38, '2038/2039', '2038-07-01', '2039-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(39, '2039/2040', '2039-07-01', '2040-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(40, '2040/2041', '2040-07-01', '2041-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(41, '2041/2042', '2041-07-01', '2042-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(42, '2042/2043', '2042-07-01', '2043-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(43, '2043/2044', '2043-07-01', '2044-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(44, '2044/2045', '2044-07-01', '2045-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(45, '2045/2046', '2045-07-01', '2046-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(46, '2046/2047', '2046-07-01', '2047-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(47, '2047/2048', '2047-07-01', '2048-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(48, '2048/2049', '2048-07-01', '2049-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(49, '2049/2050', '2049-07-01', '2050-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(50, '2050/2051', '2050-07-01', '2051-06-30', 0, '2026-01-01 01:00:00', '2026-01-01 01:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `t_users`
--

CREATE TABLE `t_users` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_users`
--

INSERT INTO `t_users` (`id`, `nama`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `update_at`) VALUES
(1, 'Wawan kurniawan, S.Pd.I', 'user01@sekolah.test', '2026-01-01 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, 'Ai Nuryidah, S.Pd', 'user02@sekolah.test', '2026-01-02 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-02 01:00:00', '2026-01-02 01:00:00'),
(3, 'Maya Permata Endah, S.Pd', 'user03@sekolah.test', '2026-01-03 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-03 01:00:00', '2026-01-03 01:00:00'),
(4, 'Dody Jaenudin, S.Pd.', 'user04@sekolah.test', '2026-01-04 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-04 01:00:00', '2026-01-04 01:00:00'),
(5, 'Dedi Kardiman, S.Pd', 'user05@sekolah.test', '2026-01-05 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-05 01:00:00', '2026-01-05 01:00:00'),
(6, 'Uca Musa Yusenda, S.Pd', 'user06@sekolah.test', '2026-01-06 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-06 01:00:00', '2026-01-06 01:00:00'),
(7, 'Drs. Ahmad Nur Mukhlis', 'user07@sekolah.test', '2026-01-07 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-07 01:00:00', '2026-01-07 01:00:00'),
(8, 'Drs. Heri', 'user08@sekolah.test', '2026-01-08 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-08 01:00:00', '2026-01-08 01:00:00'),
(9, 'Asep Bastaman', 'user09@sekolah.test', '2026-01-09 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-09 01:00:00', '2026-01-09 01:00:00'),
(10, 'H. Encep Maemun, S.T., M.Pd', 'user10@sekolah.test', '2026-01-10 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-10 01:00:00', '2026-01-10 01:00:00'),
(11, 'Opik', 'user11@sekolah.test', '2026-01-11 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-11 01:00:00', '2026-01-11 01:00:00'),
(12, 'Ida Farida, S.Pd', 'user12@sekolah.test', '2026-01-12 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-12 01:00:00', '2026-01-12 01:00:00'),
(13, 'Idah Mahmudah, S.Pd', 'user13@sekolah.test', '2026-01-13 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-13 01:00:00', '2026-01-13 01:00:00'),
(14, 'Drs. Muhamad Ali', 'user14@sekolah.test', '2026-01-14 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-14 01:00:00', '2026-01-14 01:00:00'),
(15, 'Helmi Ali Akbar, S.Pd.I', 'user15@sekolah.test', '2026-01-15 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-15 01:00:00', '2026-01-15 01:00:00'),
(16, 'Syeni Suswarini, S.Pd', 'user16@sekolah.test', '2026-01-16 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-16 01:00:00', '2026-01-16 01:00:00'),
(17, 'Wiwid Yulianti, S.Pd', 'user17@sekolah.test', '2026-01-17 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-17 01:00:00', '2026-01-17 01:00:00'),
(18, 'Ansor Muhajir, S.T', 'user18@sekolah.test', '2026-01-18 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-18 01:00:00', '2026-01-18 01:00:00'),
(19, 'Ogi Gaosun Jajang, S.Pd.I', 'user19@sekolah.test', '2026-01-19 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-19 01:00:00', '2026-01-19 01:00:00'),
(20, 'Sandi Mulyana, S.Pd', 'user20@sekolah.test', '2026-01-20 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-20 01:00:00', '2026-01-20 01:00:00'),
(21, 'Erna Suryani, S.Pd', 'user21@sekolah.test', '2026-01-21 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-21 01:00:00', '2026-01-21 01:00:00'),
(22, 'Ai Wahidah, S.Ag', 'user22@sekolah.test', '2026-01-22 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-22 01:00:00', '2026-01-22 01:00:00'),
(23, 'Chandra Aditya Yusup, S.Pd', 'user23@sekolah.test', '2026-01-23 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-23 01:00:00', '2026-01-23 01:00:00'),
(24, 'Arif Kusmawan, S.Pd', 'user24@sekolah.test', '2026-01-24 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-24 01:00:00', '2026-01-24 01:00:00'),
(25, 'Asep Taufiq Hidayat, S.Kom', 'user25@sekolah.test', '2026-01-25 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-25 01:00:00', '2026-01-25 01:00:00'),
(26, 'Rendy Ichsan Kusumah, S.T', 'user26@sekolah.test', '2026-01-26 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-26 01:00:00', '2026-01-26 01:00:00'),
(27, 'Feri Rahmawan, S.Pd', 'user27@sekolah.test', '2026-01-27 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-27 01:00:00', '2026-01-27 01:00:00'),
(28, 'Ari Sandriana, S.Kom', 'user28@sekolah.test', '2026-01-28 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-28 01:00:00', '2026-01-28 01:00:00'),
(29, 'Yaneu Nurdian, S.Pd., M.Pd', 'user29@sekolah.test', '2026-01-01 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, 'Ucu Muhammad Khoer N, S.Pd', 'user30@sekolah.test', '2026-01-02 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-02 01:00:00', '2026-01-02 01:00:00'),
(31, 'Mira Dwi Rahmawati, S.Ag', 'user31@sekolah.test', '2026-01-03 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-03 01:00:00', '2026-01-03 01:00:00'),
(32, 'Aldi Maulana, S.Pd', 'user32@sekolah.test', '2026-01-04 01:00:00', '$2y$10$rgVksLn.IyQ8MjSBEHDqMOjd6mpOccj7ohx/DZlQJ1i6HtbLRIrDO', '', 'wali_kelas', '2026-01-04 01:00:00', '2026-01-04 01:00:00'),
(33, 'Eli Rosliah, S.Pd', 'user33@sekolah.test', '2026-01-05 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-05 01:00:00', '2026-01-05 01:00:00'),
(34, 'Isni Nur Fitriani, S.Pd', 'user34@sekolah.test', '2026-01-06 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-06 01:00:00', '2026-01-06 01:00:00'),
(35, 'Aanisah Putri Nur Aini, S.Pd', 'user35@sekolah.test', '2026-01-07 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-07 01:00:00', '2026-01-07 01:00:00'),
(36, 'Yusi Luthfiani, S.Sos.I', 'user36@sekolah.test', '2026-01-08 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-08 01:00:00', '2026-01-08 01:00:00'),
(37, 'Lilis Rustandy Mawardi, S.Pd., M.Pd', 'user37@sekolah.test', '2026-01-09 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-09 01:00:00', '2026-01-09 01:00:00'),
(38, 'Yusrin Noer Sabrina, S.Pd', 'user38@sekolah.test', '2026-01-10 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-10 01:00:00', '2026-01-10 01:00:00'),
(39, 'Senny Indayani, S.Pd', 'user39@sekolah.test', '2026-01-11 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-11 01:00:00', '2026-01-11 01:00:00'),
(40, 'Ilma Amalia, S.Pd', 'user40@sekolah.test', '2026-01-12 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-12 01:00:00', '2026-01-12 01:00:00'),
(41, 'Alvira Sofwatunnisa, S.Pd', 'user41@sekolah.test', '2026-01-13 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-13 01:00:00', '2026-01-13 01:00:00'),
(42, 'Risna Andriyani, S.Pd', 'user42@sekolah.test', '2026-01-14 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-14 01:00:00', '2026-01-14 01:00:00'),
(43, 'Dra. Tintin Noorhayati', 'user43@sekolah.test', '2026-01-15 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-15 01:00:00', '2026-01-15 01:00:00'),
(44, 'Rizkia Fahira, S.Pd', 'user44@sekolah.test', '2026-01-16 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-16 01:00:00', '2026-01-16 01:00:00'),
(45, 'Frasetio', 'user45@sekolah.test', '2026-01-17 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-17 01:00:00', '2026-01-17 01:00:00'),
(46, 'Ratnaningsih Hatil Aeni, S.Pd', 'user46@sekolah.test', '2026-01-18 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-18 01:00:00', '2026-01-18 01:00:00'),
(47, 'Dheny Darajatul Hasani, S.Pd', 'user47@sekolah.test', '2026-01-19 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-19 01:00:00', '2026-01-19 01:00:00'),
(48, 'Fahrullah, S.Pd', 'user48@sekolah.test', '2026-01-20 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-20 01:00:00', '2026-01-20 01:00:00'),
(49, 'Aan Andriana, S.T', 'user49@sekolah.test', '2026-01-21 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-21 01:00:00', '2026-01-21 01:00:00'),
(50, 'Fitri Fauziah, S.T', 'user50@sekolah.test', '2026-01-22 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-22 01:00:00', '2026-01-22 01:00:00'),
(61, 'Riesy Putri Navia, S.Kom', 'user51@sekolah.test', NULL, '$2y$10$JDNJu7McJdx4/I/MyGFKB.bCtPASZgItTgWOiiZVfwi49Fap7B.wK', NULL, 'guru', NULL, NULL),
(62, 'Ai Nuryidah, S.Pd', 'user52@sekolah.test', NULL, '$2y$10$DAOXmZ/wdVsuhUyHedAOO.KDjDHf0TFebe3KnSxOKwi9L1Hqz/bxy', NULL, 'guru', NULL, NULL),
(63, 'Irvan Rizal, S.Pd', 'user53@sekolah.test', NULL, '$2y$10$DRy7VGh07ziQXkTPXx221.GClOc/Xt/uRbAlYl9VYlaD9Ie9pbhp.', NULL, 'guru', NULL, NULL),
(64, 'Nida Aulia, S.Pd', 'user54@sekolah.test', NULL, '$2y$10$uHyKL15AmaEUfIIE8cgfzubJl6l.t1zsKq4D1iD3gfOfQYE0Rega.', NULL, 'guru', NULL, NULL),
(65, 'Sri Nurrizki Putri Krisnadi, S.Pd', 'user55@sekolah.test', NULL, '$2y$10$BeeDHj2snsCYCh9tGE5eiO7TZOUdFj6AxDR5HtxBLqeI2X2zINN9W', NULL, 'guru', NULL, NULL),
(66, 'Rurry Febriani Maryadi, S.Pd', 'user56@sekolah.test', NULL, '$2y$10$lKGABzTCQV89saUhN3cfi.s2fJ8T9XuF.9ATIgc6.N5FkfaorJP9C', NULL, 'guru', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_wali_kelas`
--

CREATE TABLE `t_wali_kelas` (
  `id` int(11) NOT NULL,
  `tahun_ajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_wali_kelas`
--

INSERT INTO `t_wali_kelas` (`id`, `tahun_ajaran_id`, `kelas_id`, `guru_id`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `created_at`, `update_at`) VALUES
(6, 26, 6, 6, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(7, 26, 7, 7, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(8, 26, 8, 8, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(9, 26, 9, 9, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(10, 26, 10, 10, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(11, 26, 11, 11, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(12, 26, 12, 12, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(13, 26, 13, 13, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(14, 26, 14, 14, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(15, 26, 15, 15, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(16, 26, 16, 16, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(17, 26, 17, 17, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(18, 26, 18, 18, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(19, 26, 19, 19, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(20, 26, 20, 20, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(21, 26, 21, 21, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(22, 26, 22, 22, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(23, 26, 23, 23, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(24, 26, 24, 24, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(25, 26, 25, 25, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(26, 26, 26, 26, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(27, 26, 27, 27, '2026-07-01', '2026-11-05', 0, '2026-07-01 01:00:00', '2026-11-05 03:45:36'),
(28, 26, 28, 28, '2026-07-01', '2026-11-05', 0, '2026-07-01 01:00:00', '2026-11-05 03:45:36'),
(29, 26, 29, 29, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(30, 26, 30, 30, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(31, 26, 31, 31, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(32, 26, 2, 32, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(33, 26, 33, 33, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(34, 26, 34, 34, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(35, 26, 35, 35, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(36, 26, 36, 36, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(37, 26, 37, 37, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(38, 26, 38, 38, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(39, 26, 39, 39, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(40, 26, 40, 40, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(41, 26, 41, 41, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(42, 26, 42, 42, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(43, 26, 43, 43, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(44, 26, 44, 44, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(45, 26, 45, 45, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(46, 26, 46, 46, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(47, 26, 47, 47, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(48, 26, 48, 48, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(49, 26, 49, 49, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(50, 26, 50, 50, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_guru`
--
ALTER TABLE `t_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `t_kelas`
--
ALTER TABLE `t_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indexes for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggaran_kategori_id` (`pelanggaran_kategori_id`);

--
-- Indexes for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `pelanggaran_id` (`pelanggaran_id`),
  ADD KEY `pelanggaran_kategori_id` (`pelanggaran_kategori_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indexes for table `t_siswa`
--
ALTER TABLE `t_siswa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tahun_ajaran_id` (`tahun_ajaran_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_guru`
--
ALTER TABLE `t_guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_guru`
--
ALTER TABLE `t_guru`
  ADD CONSTRAINT `t_guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `t_users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  ADD CONSTRAINT `t_kelas_siswa_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_kelas_siswa_ibfk_2` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_kelas_siswa_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  ADD CONSTRAINT `t_pelanggaran_ibfk_1` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `t_siswa` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_4` FOREIGN KEY (`pelanggaran_id`) REFERENCES `t_pelanggaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_5` FOREIGN KEY (`pelanggaran_kategori_id`) REFERENCES `t_pelanggaran_kategori` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_pelanggaran_siswa_ibfk_6` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  ADD CONSTRAINT `t_wali_kelas_ibfk_1` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `t_tahun_ajaran` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_wali_kelas_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `t_kelas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `t_wali_kelas_ibfk_3` FOREIGN KEY (`guru_id`) REFERENCES `t_guru` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
