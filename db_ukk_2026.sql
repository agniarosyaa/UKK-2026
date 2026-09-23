-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2026 at 07:28 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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
(1, '1980100001', 'Guru 01', 'guru01@sekolah.test', 1, 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, '1980200002', 'Guru 02', 'guru02@sekolah.test', 1, 2, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(3, '1980300003', 'Guru 03', 'guru03@sekolah.test', 1, 3, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(4, '1980400004', 'Guru 04', 'guru04@sekolah.test', 1, 4, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(5, '1980500005', 'Guru 05', 'guru05@sekolah.test', 1, 5, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(6, '1980600006', 'Guru 06', 'guru06@sekolah.test', 1, 6, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(7, '1980700007', 'Guru 07', 'guru07@sekolah.test', 1, 7, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(8, '1980800008', 'Guru 08', 'guru08@sekolah.test', 1, 8, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(9, '1980900009', 'Guru 09', 'guru09@sekolah.test', 1, 9, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(10, '1981000010', 'Guru 10', 'guru10@sekolah.test', 1, 10, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(11, '1981100011', 'Guru 11', 'guru11@sekolah.test', 1, 11, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(12, '1981200012', 'Guru 12', 'guru12@sekolah.test', 1, 12, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(13, '1981300013', 'Guru 13', 'guru13@sekolah.test', 1, 13, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(14, '1981400014', 'Guru 14', 'guru14@sekolah.test', 1, 14, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(15, '1981500015', 'Guru 15', 'guru15@sekolah.test', 1, 15, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(16, '1981600016', 'Guru 16', 'guru16@sekolah.test', 1, 16, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(17, '1981700017', 'Guru 17', 'guru17@sekolah.test', 1, 17, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(18, '1981800018', 'Guru 18', 'guru18@sekolah.test', 1, 18, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(19, '1981900019', 'Guru 19', 'guru19@sekolah.test', 1, 19, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(20, '1982000020', 'Guru 20', 'guru20@sekolah.test', 1, 20, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(21, '1982100021', 'Guru 21', 'guru21@sekolah.test', 1, 21, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(22, '1982200022', 'Guru 22', 'guru22@sekolah.test', 1, 22, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(23, '1982300023', 'Guru 23', 'guru23@sekolah.test', 1, 23, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(24, '1982400024', 'Guru 24', 'guru24@sekolah.test', 1, 24, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(25, '1982500025', 'Guru 25', 'guru25@sekolah.test', 1, 25, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(26, '1982600026', 'Guru 26', 'guru26@sekolah.test', 1, 26, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(27, '1982700027', 'Guru 27', 'guru27@sekolah.test', 1, 27, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(28, '1982800028', 'Guru 28', 'guru28@sekolah.test', 1, 28, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(29, '1982900029', 'Guru 29', 'guru29@sekolah.test', 1, 29, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, '1983000030', 'Guru 30', 'guru30@sekolah.test', 1, 30, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(31, '1983100031', 'Guru 31', 'guru31@sekolah.test', 1, 31, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(32, '1983200032', 'Guru 32', 'guru32@sekolah.test', 1, 32, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(33, '1983300033', 'Guru 33', 'guru33@sekolah.test', 1, 33, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(34, '1983400034', 'Guru 34', 'guru34@sekolah.test', 1, 34, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(35, '1983500035', 'Guru 35', 'guru35@sekolah.test', 1, 35, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(36, '1983600036', 'Guru 36', 'guru36@sekolah.test', 1, 36, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(37, '1983700037', 'Guru 37', 'guru37@sekolah.test', 1, 37, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(38, '1983800038', 'Guru 38', 'guru38@sekolah.test', 1, 38, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(39, '1983900039', 'Guru 39', 'guru39@sekolah.test', 1, 39, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(40, '1984000040', 'Guru 40', 'guru40@sekolah.test', 1, 40, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(41, '1984100041', 'Guru 41', 'guru41@sekolah.test', 1, 41, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(42, '1984200042', 'Guru 42', 'guru42@sekolah.test', 1, 42, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(43, '1984300043', 'Guru 43', 'guru43@sekolah.test', 1, 43, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(44, '1984400044', 'Guru 44', 'guru44@sekolah.test', 1, 44, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(45, '1984500045', 'Guru 45', 'guru45@sekolah.test', 1, 45, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(46, '1984600046', 'Guru 46', 'guru46@sekolah.test', 1, 46, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(47, '1984700047', 'Guru 47', 'guru47@sekolah.test', 1, 47, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(48, '1984800048', 'Guru 48', 'guru48@sekolah.test', 1, 48, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(49, '1984900049', 'Guru 49', 'guru49@sekolah.test', 1, 49, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(50, '1985000050', 'Guru 50', 'guru50@sekolah.test', 1, 50, '2026-01-01 01:00:00', '2026-01-01 01:00:00');

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
(1, '10 RPL 1', '10', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, '11 TKJ 1', '11', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(3, '12 DKV 1', '12', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(4, '10 AKL 2', '10', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(5, '11 MPLB 2', '11', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(6, '12 RPL 2', '12', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(7, '10 TKJ 3', '10', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(8, '11 DKV 3', '11', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(9, '12 AKL 3', '12', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(10, '10 MPLB 4', '10', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(11, '11 RPL 4', '11', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(12, '12 TKJ 4', '12', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(13, '10 DKV 5', '10', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(14, '11 AKL 5', '11', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(15, '12 MPLB 5', '12', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(16, '10 RPL 6', '10', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(17, '11 TKJ 6', '11', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(18, '12 DKV 6', '12', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(19, '10 AKL 7', '10', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(20, '11 MPLB 7', '11', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(21, '12 RPL 7', '12', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(22, '10 TKJ 8', '10', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(23, '11 DKV 8', '11', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(24, '12 AKL 8', '12', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(25, '10 MPLB 9', '10', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(26, '11 RPL 9', '11', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(27, '12 TKJ 9', '12', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(28, '10 DKV 10', '10', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(29, '11 AKL 10', '11', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, '12 MPLB 10', '12', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(31, '10 RPL 11', '10', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(32, '11 TKJ 11', '11', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(33, '12 DKV 11', '12', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(34, '10 AKL 12', '10', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(35, '11 MPLB 12', '11', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(36, '12 RPL 12', '12', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(37, '10 TKJ 13', '10', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(38, '11 DKV 13', '11', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(39, '12 AKL 13', '12', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(40, '10 MPLB 14', '10', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(41, '11 RPL 14', '11', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(42, '12 TKJ 14', '12', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(43, '10 DKV 15', '10', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(44, '11 AKL 15', '11', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(45, '12 MPLB 15', '12', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(46, '10 RPL 16', '10', 'RPL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(47, '11 TKJ 16', '11', 'TKJ', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(48, '12 DKV 16', '12', 'DKV', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(49, '10 AKL 17', '10', 'AKL', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(50, '11 MPLB 17', '11', 'MPLB', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00');

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
(1, 1, 26, 1, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(2, 2, 26, 2, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(3, 3, 26, 3, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(4, 4, 26, 4, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(5, 5, 26, 5, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(6, 6, 26, 6, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(7, 7, 26, 7, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(8, 8, 26, 8, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(9, 9, 26, 9, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(10, 10, 26, 10, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(11, 11, 26, 11, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(12, 12, 26, 12, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(13, 13, 26, 13, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(14, 14, 26, 14, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(15, 15, 26, 15, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(16, 16, 26, 16, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(17, 17, 26, 17, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(18, 18, 26, 18, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(19, 19, 26, 19, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(20, 20, 26, 20, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(21, 21, 26, 21, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(22, 22, 26, 22, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(23, 23, 26, 23, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(24, 24, 26, 24, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(25, 25, 26, 25, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(26, 26, 26, 26, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(27, 27, 26, 27, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(28, 28, 26, 28, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(29, 29, 26, 29, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(30, 30, 26, 30, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(31, 31, 26, 31, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(32, 32, 26, 32, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(33, 33, 26, 33, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(34, 34, 26, 34, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(35, 35, 26, 35, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(36, 36, 26, 36, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(37, 37, 26, 37, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(38, 38, 26, 38, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(39, 39, 26, 39, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(40, 40, 26, 40, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(41, 41, 26, 41, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(42, 42, 26, 42, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(43, 43, 26, 43, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(44, 44, 26, 44, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(45, 45, 26, 45, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(46, 46, 26, 46, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(47, 47, 26, 47, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(48, 48, 26, 48, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(49, 49, 26, 49, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(50, 50, 26, 50, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00');

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
(1, 1, 'PLG-001', 'Terlambat masuk sekolah 1', 2, 'Deskripsi untuk terlambat masuk sekolah 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, 2, 'PLG-002', 'Tidak memakai atribut lengkap ', 3, 'Deskripsi untuk tidak memakai atribut lengkap 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(3, 3, 'PLG-003', 'Tidak mengerjakan tugas 1', 4, 'Deskripsi untuk tidak mengerjakan tugas 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(4, 4, 'PLG-004', 'Tidak hadir tanpa keterangan 1', 5, 'Deskripsi untuk tidak hadir tanpa keterangan 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
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
(45, 45, 'PLG-045', 'Membuang sampah sembarangan 5', 6, 'Deskripsi untuk membuang sampah sembarangan 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(46, 46, 'PLG-046', 'Ribut di kelas 5', 7, 'Deskripsi untuk ribut di kelas 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(47, 47, 'PLG-047', 'Seragam tidak sesuai 5', 8, 'Deskripsi untuk seragam tidak sesuai 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(48, 48, 'PLG-048', 'Keluar kelas tanpa izin 5', 9, 'Deskripsi untuk keluar kelas tanpa izin 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(49, 49, 'PLG-049', 'Berkata tidak sopan 5', 10, 'Deskripsi untuk berkata tidak sopan 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(50, 50, 'PLG-050', 'Tidak membawa perlengkapan 5', 1, 'Deskripsi untuk tidak membawa perlengkapan 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00');

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
(1, 'Kedisiplinan 1', 'Kategori pelanggaran kedisiplinan 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, 'Kerapian 1', 'Kategori pelanggaran kerapian 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(3, 'Kehadiran 1', 'Kategori pelanggaran kehadiran 1', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
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
(50, 'Akademik 5', 'Kategori pelanggaran akademik 5', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00');

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
(1, 26, 1, 'Andi Pratama', 1, '10 RPL 1', 1, 'Terlambat masuk sekolah 1', 1, 1, 'Guru 01', '2026-08-01', 'Catatan pelanggaran siswa ke-1', 2, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(2, 26, 2, 'Budi Pratama', 2, '11 TKJ 1', 2, 'Tidak memakai atribut lengkap 1', 2, 2, 'Guru 02', '2026-08-02', 'Catatan pelanggaran siswa ke-2', 3, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(3, 26, 3, 'Citra Pratama', 3, '12 DKV 1', 3, 'Tidak mengerjakan tugas 1', 3, 3, 'Guru 03', '2026-08-03', 'Catatan pelanggaran siswa ke-3', 4, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(4, 26, 4, 'Dinda Pratama', 4, '10 AKL 2', 4, 'Tidak hadir tanpa keterangan 1', 4, 4, 'Guru 04', '2026-08-04', 'Catatan pelanggaran siswa ke-4', 5, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(5, 26, 5, 'Eka Pratama', 5, '11 MPLB 2', 5, 'Membuang sampah sembarangan 1', 5, 5, 'Guru 05', '2026-08-05', 'Catatan pelanggaran siswa ke-5', 6, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(6, 26, 6, 'Fajar Pratama', 6, '12 RPL 2', 6, 'Ribut di kelas 1', 6, 6, 'Guru 06', '2026-08-06', 'Catatan pelanggaran siswa ke-6', 7, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(7, 26, 7, 'Gina Pratama', 7, '10 TKJ 3', 7, 'Seragam tidak sesuai 1', 7, 7, 'Guru 07', '2026-08-07', 'Catatan pelanggaran siswa ke-7', 8, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(8, 26, 8, 'Hadi Pratama', 8, '11 DKV 3', 8, 'Keluar kelas tanpa izin 1', 8, 8, 'Guru 08', '2026-08-08', 'Catatan pelanggaran siswa ke-8', 9, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(9, 26, 9, 'Intan Pratama', 9, '12 AKL 3', 9, 'Berkata tidak sopan 1', 9, 9, 'Guru 09', '2026-08-09', 'Catatan pelanggaran siswa ke-9', 10, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(10, 26, 10, 'Joko Pratama', 10, '10 MPLB 4', 10, 'Tidak membawa perlengkapan 1', 10, 10, 'Guru 10', '2026-08-10', 'Catatan pelanggaran siswa ke-10', 1, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(11, 26, 11, 'Kania Pratama', 11, '11 RPL 4', 11, 'Terlambat masuk sekolah 2', 11, 11, 'Guru 11', '2026-08-11', 'Catatan pelanggaran siswa ke-11', 2, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(12, 26, 12, 'Lutfi Pratama', 12, '12 TKJ 4', 12, 'Tidak memakai atribut lengkap 2', 12, 12, 'Guru 12', '2026-08-12', 'Catatan pelanggaran siswa ke-12', 3, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(13, 26, 13, 'Maya Pratama', 13, '10 DKV 5', 13, 'Tidak mengerjakan tugas 2', 13, 13, 'Guru 13', '2026-08-13', 'Catatan pelanggaran siswa ke-13', 4, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(14, 26, 14, 'Nanda Pratama', 14, '11 AKL 5', 14, 'Tidak hadir tanpa keterangan 2', 14, 14, 'Guru 14', '2026-08-14', 'Catatan pelanggaran siswa ke-14', 5, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(15, 26, 15, 'Oki Pratama', 15, '12 MPLB 5', 15, 'Membuang sampah sembarangan 2', 15, 15, 'Guru 15', '2026-08-15', 'Catatan pelanggaran siswa ke-15', 6, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(16, 26, 16, 'Putri Pratama', 16, '10 RPL 6', 16, 'Ribut di kelas 2', 16, 16, 'Guru 16', '2026-08-16', 'Catatan pelanggaran siswa ke-16', 7, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(17, 26, 17, 'Qori Pratama', 17, '11 TKJ 6', 17, 'Seragam tidak sesuai 2', 17, 17, 'Guru 17', '2026-08-17', 'Catatan pelanggaran siswa ke-17', 8, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(18, 26, 18, 'Raka Pratama', 18, '12 DKV 6', 18, 'Keluar kelas tanpa izin 2', 18, 18, 'Guru 18', '2026-08-18', 'Catatan pelanggaran siswa ke-18', 9, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(19, 26, 19, 'Salsa Pratama', 19, '10 AKL 7', 19, 'Berkata tidak sopan 2', 19, 19, 'Guru 19', '2026-08-19', 'Catatan pelanggaran siswa ke-19', 10, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(20, 26, 20, 'Tio Pratama', 20, '11 MPLB 7', 20, 'Tidak membawa perlengkapan 2', 20, 20, 'Guru 20', '2026-08-20', 'Catatan pelanggaran siswa ke-20', 1, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(21, 26, 21, 'Uli Pratama', 21, '12 RPL 7', 21, 'Terlambat masuk sekolah 3', 21, 21, 'Guru 21', '2026-08-21', 'Catatan pelanggaran siswa ke-21', 2, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(22, 26, 22, 'Vina Pratama', 22, '10 TKJ 8', 22, 'Tidak memakai atribut lengkap 3', 22, 22, 'Guru 22', '2026-08-22', 'Catatan pelanggaran siswa ke-22', 3, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(23, 26, 23, 'Wahyu Pratama', 23, '11 DKV 8', 23, 'Tidak mengerjakan tugas 3', 23, 23, 'Guru 23', '2026-08-23', 'Catatan pelanggaran siswa ke-23', 4, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(24, 26, 24, 'Yani Pratama', 24, '12 AKL 8', 24, 'Tidak hadir tanpa keterangan 3', 24, 24, 'Guru 24', '2026-08-24', 'Catatan pelanggaran siswa ke-24', 5, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(25, 26, 25, 'Zaki Pratama', 25, '10 MPLB 9', 25, 'Membuang sampah sembarangan 3', 25, 25, 'Guru 25', '2026-08-25', 'Catatan pelanggaran siswa ke-25', 6, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(26, 26, 26, 'Andi Saputra', 26, '11 RPL 9', 26, 'Ribut di kelas 3', 26, 26, 'Guru 26', '2026-08-26', 'Catatan pelanggaran siswa ke-26', 7, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(27, 26, 27, 'Budi Saputra', 27, '12 TKJ 9', 27, 'Seragam tidak sesuai 3', 27, 27, 'Guru 27', '2026-08-27', 'Catatan pelanggaran siswa ke-27', 8, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(28, 26, 28, 'Citra Saputra', 28, '10 DKV 10', 28, 'Keluar kelas tanpa izin 3', 28, 28, 'Guru 28', '2026-08-28', 'Catatan pelanggaran siswa ke-28', 9, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(29, 26, 29, 'Dinda Saputra', 29, '11 AKL 10', 29, 'Berkata tidak sopan 3', 29, 29, 'Guru 29', '2026-08-29', 'Catatan pelanggaran siswa ke-29', 10, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(30, 26, 30, 'Eka Saputra', 30, '12 MPLB 10', 30, 'Tidak membawa perlengkapan 3', 30, 30, 'Guru 30', '2026-08-30', 'Catatan pelanggaran siswa ke-30', 1, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(31, 26, 31, 'Fajar Saputra', 31, '10 RPL 11', 31, 'Terlambat masuk sekolah 4', 31, 31, 'Guru 31', '2026-08-31', 'Catatan pelanggaran siswa ke-31', 2, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(32, 26, 32, 'Gina Saputra', 32, '11 TKJ 11', 32, 'Tidak memakai atribut lengkap 4', 32, 32, 'Guru 32', '2026-09-01', 'Catatan pelanggaran siswa ke-32', 3, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(33, 26, 33, 'Hadi Saputra', 33, '12 DKV 11', 33, 'Tidak mengerjakan tugas 4', 33, 33, 'Guru 33', '2026-09-02', 'Catatan pelanggaran siswa ke-33', 4, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(34, 26, 34, 'Intan Saputra', 34, '10 AKL 12', 34, 'Tidak hadir tanpa keterangan 4', 34, 34, 'Guru 34', '2026-09-03', 'Catatan pelanggaran siswa ke-34', 5, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(35, 26, 35, 'Joko Saputra', 35, '11 MPLB 12', 35, 'Membuang sampah sembarangan 4', 35, 35, 'Guru 35', '2026-09-04', 'Catatan pelanggaran siswa ke-35', 6, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(36, 26, 36, 'Kania Saputra', 36, '12 RPL 12', 36, 'Ribut di kelas 4', 36, 36, 'Guru 36', '2026-09-05', 'Catatan pelanggaran siswa ke-36', 7, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(37, 26, 37, 'Lutfi Saputra', 37, '10 TKJ 13', 37, 'Seragam tidak sesuai 4', 37, 37, 'Guru 37', '2026-09-06', 'Catatan pelanggaran siswa ke-37', 8, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(38, 26, 38, 'Maya Saputra', 38, '11 DKV 13', 38, 'Keluar kelas tanpa izin 4', 38, 38, 'Guru 38', '2026-09-07', 'Catatan pelanggaran siswa ke-38', 9, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(39, 26, 39, 'Nanda Saputra', 39, '12 AKL 13', 39, 'Berkata tidak sopan 4', 39, 39, 'Guru 39', '2026-09-08', 'Catatan pelanggaran siswa ke-39', 10, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(40, 26, 40, 'Oki Saputra', 40, '10 MPLB 14', 40, 'Tidak membawa perlengkapan 4', 40, 40, 'Guru 40', '2026-09-09', 'Catatan pelanggaran siswa ke-40', 1, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(41, 26, 41, 'Putri Saputra', 41, '11 RPL 14', 41, 'Terlambat masuk sekolah 5', 41, 41, 'Guru 41', '2026-09-10', 'Catatan pelanggaran siswa ke-41', 2, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(42, 26, 42, 'Qori Saputra', 42, '12 TKJ 14', 42, 'Tidak memakai atribut lengkap 5', 42, 42, 'Guru 42', '2026-09-11', 'Catatan pelanggaran siswa ke-42', 3, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(43, 26, 43, 'Raka Saputra', 43, '10 DKV 15', 43, 'Tidak mengerjakan tugas 5', 43, 43, 'Guru 43', '2026-09-12', 'Catatan pelanggaran siswa ke-43', 4, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(44, 26, 44, 'Salsa Saputra', 44, '11 AKL 15', 44, 'Tidak hadir tanpa keterangan 5', 44, 44, 'Guru 44', '2026-09-13', 'Catatan pelanggaran siswa ke-44', 5, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(45, 26, 45, 'Tio Saputra', 45, '12 MPLB 15', 45, 'Membuang sampah sembarangan 5', 45, 45, 'Guru 45', '2026-09-14', 'Catatan pelanggaran siswa ke-45', 6, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(46, 26, 46, 'Uli Saputra', 46, '10 RPL 16', 46, 'Ribut di kelas 5', 46, 46, 'Guru 46', '2026-09-15', 'Catatan pelanggaran siswa ke-46', 7, 'Teguran lisan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(47, 26, 47, 'Vina Saputra', 47, '11 TKJ 16', 47, 'Seragam tidak sesuai 5', 47, 47, 'Guru 47', '2026-09-16', 'Catatan pelanggaran siswa ke-47', 8, 'Peringatan tertulis', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(48, 26, 48, 'Wahyu Saputra', 48, '12 DKV 16', 48, 'Keluar kelas tanpa izin 5', 48, 48, 'Guru 48', '2026-09-17', 'Catatan pelanggaran siswa ke-48', 9, 'Pembinaan', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(49, 26, 49, 'Yani Saputra', 49, '10 AKL 17', 49, 'Berkata tidak sopan 5', 49, 49, 'Guru 49', '2026-09-18', 'Catatan pelanggaran siswa ke-49', 10, 'Pemanggilan orang tua', 'Selesai', '2026-08-01 01:00:00', '2026-08-01 01:00:00'),
(50, 26, 50, 'Zaki Saputra', 50, '11 MPLB 17', 50, 'Tidak membawa perlengkapan 5', 50, 50, 'Guru 50', '2026-09-19', 'Catatan pelanggaran siswa ke-50', 1, 'Konseling', 'Diproses', '2026-08-01 01:00:00', '2026-08-01 01:00:00');

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
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `status_aktif` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_siswa`
--

INSERT INTO `t_siswa` (`id`, `nis`, `nisn`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `status_aktif`, `created_at`, `update_at`) VALUES
(1, 'NIS20260001', '30000001', 'Andi Pratama', 'L', '2009-02-04', 'Jl. Pendidikan No. 1, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, 'NIS20260002', '30000002', 'Budi Pratama', 'P', '2010-03-07', 'Jl. Pendidikan No. 2, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(3, 'NIS20260003', '30000003', 'Citra Pratama', 'L', '2011-04-10', 'Jl. Pendidikan No. 3, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(4, 'NIS20260004', '30000004', 'Dinda Pratama', 'P', '2008-05-13', 'Jl. Pendidikan No. 4, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(5, 'NIS20260005', '30000005', 'Eka Pratama', 'L', '2009-06-16', 'Jl. Pendidikan No. 5, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(6, 'NIS20260006', '30000006', 'Fajar Pratama', 'P', '2010-07-19', 'Jl. Pendidikan No. 6, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(7, 'NIS20260007', '30000007', 'Gina Pratama', 'L', '2011-08-22', 'Jl. Pendidikan No. 7, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(8, 'NIS20260008', '30000008', 'Hadi Pratama', 'P', '2008-09-25', 'Jl. Pendidikan No. 8, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(9, 'NIS20260009', '30000009', 'Intan Pratama', 'L', '2009-10-01', 'Jl. Pendidikan No. 9, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(10, 'NIS20260010', '30000010', 'Joko Pratama', 'P', '2010-11-04', 'Jl. Pendidikan No. 10, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(11, 'NIS20260011', '30000011', 'Kania Pratama', 'L', '2011-12-07', 'Jl. Pendidikan No. 11, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(12, 'NIS20260012', '30000012', 'Lutfi Pratama', 'P', '2008-01-10', 'Jl. Pendidikan No. 12, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(13, 'NIS20260013', '30000013', 'Maya Pratama', 'L', '2009-02-13', 'Jl. Pendidikan No. 13, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(14, 'NIS20260014', '30000014', 'Nanda Pratama', 'P', '2010-03-16', 'Jl. Pendidikan No. 14, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(15, 'NIS20260015', '30000015', 'Oki Pratama', 'L', '2011-04-19', 'Jl. Pendidikan No. 15, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(16, 'NIS20260016', '30000016', 'Putri Pratama', 'P', '2008-05-22', 'Jl. Pendidikan No. 16, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(17, 'NIS20260017', '30000017', 'Qori Pratama', 'L', '2009-06-25', 'Jl. Pendidikan No. 17, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(18, 'NIS20260018', '30000018', 'Raka Pratama', 'P', '2010-07-01', 'Jl. Pendidikan No. 18, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(19, 'NIS20260019', '30000019', 'Salsa Pratama', 'L', '2011-08-04', 'Jl. Pendidikan No. 19, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(20, 'NIS20260020', '30000020', 'Tio Pratama', 'P', '2008-09-07', 'Jl. Pendidikan No. 20, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(21, 'NIS20260021', '30000021', 'Uli Pratama', 'L', '2009-10-10', 'Jl. Pendidikan No. 21, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(22, 'NIS20260022', '30000022', 'Vina Pratama', 'P', '2010-11-13', 'Jl. Pendidikan No. 22, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(23, 'NIS20260023', '30000023', 'Wahyu Pratama', 'L', '2011-12-16', 'Jl. Pendidikan No. 23, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(24, 'NIS20260024', '30000024', 'Yani Pratama', 'P', '2008-01-19', 'Jl. Pendidikan No. 24, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(25, 'NIS20260025', '30000025', 'Zaki Pratama', 'L', '2009-02-22', 'Jl. Pendidikan No. 25, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(26, 'NIS20260026', '30000026', 'Andi Saputra', 'P', '2010-03-25', 'Jl. Pendidikan No. 26, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(27, 'NIS20260027', '30000027', 'Budi Saputra', 'L', '2011-04-01', 'Jl. Pendidikan No. 27, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(28, 'NIS20260028', '30000028', 'Citra Saputra', 'P', '2008-05-04', 'Jl. Pendidikan No. 28, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(29, 'NIS20260029', '30000029', 'Dinda Saputra', 'L', '2009-06-07', 'Jl. Pendidikan No. 29, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, 'NIS20260030', '30000030', 'Eka Saputra', 'P', '2010-07-10', 'Jl. Pendidikan No. 30, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(31, 'NIS20260031', '30000031', 'Fajar Saputra', 'L', '2011-08-13', 'Jl. Pendidikan No. 31, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(32, 'NIS20260032', '30000032', 'Gina Saputra', 'P', '2008-09-16', 'Jl. Pendidikan No. 32, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(33, 'NIS20260033', '30000033', 'Hadi Saputra', 'L', '2009-10-19', 'Jl. Pendidikan No. 33, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(34, 'NIS20260034', '30000034', 'Intan Saputra', 'P', '2010-11-22', 'Jl. Pendidikan No. 34, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(35, 'NIS20260035', '30000035', 'Joko Saputra', 'L', '2011-12-25', 'Jl. Pendidikan No. 35, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(36, 'NIS20260036', '30000036', 'Kania Saputra', 'P', '2008-01-01', 'Jl. Pendidikan No. 36, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(37, 'NIS20260037', '30000037', 'Lutfi Saputra', 'L', '2009-02-04', 'Jl. Pendidikan No. 37, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(38, 'NIS20260038', '30000038', 'Maya Saputra', 'P', '2010-03-07', 'Jl. Pendidikan No. 38, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(39, 'NIS20260039', '30000039', 'Nanda Saputra', 'L', '2011-04-10', 'Jl. Pendidikan No. 39, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(40, 'NIS20260040', '30000040', 'Oki Saputra', 'P', '2008-05-13', 'Jl. Pendidikan No. 40, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(41, 'NIS20260041', '30000041', 'Putri Saputra', 'L', '2009-06-16', 'Jl. Pendidikan No. 41, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(42, 'NIS20260042', '30000042', 'Qori Saputra', 'P', '2010-07-19', 'Jl. Pendidikan No. 42, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(43, 'NIS20260043', '30000043', 'Raka Saputra', 'L', '2011-08-22', 'Jl. Pendidikan No. 43, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(44, 'NIS20260044', '30000044', 'Salsa Saputra', 'P', '2008-09-25', 'Jl. Pendidikan No. 44, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(45, 'NIS20260045', '30000045', 'Tio Saputra', 'L', '2009-10-01', 'Jl. Pendidikan No. 45, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(46, 'NIS20260046', '30000046', 'Uli Saputra', 'P', '2010-11-04', 'Jl. Pendidikan No. 46, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(47, 'NIS20260047', '30000047', 'Vina Saputra', 'L', '2011-12-07', 'Jl. Pendidikan No. 47, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(48, 'NIS20260048', '30000048', 'Wahyu Saputra', 'P', '2008-01-10', 'Jl. Pendidikan No. 48, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(49, 'NIS20260049', '30000049', 'Yani Saputra', 'L', '2009-02-13', 'Jl. Pendidikan No. 49, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(50, 'NIS20260050', '30000050', 'Zaki Saputra', 'P', '2010-03-16', 'Jl. Pendidikan No. 50, Tasikmalaya', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00');

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
(1, '2001/2002', '2001-07-01', '2002-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, '2002/2003', '2002-07-01', '2003-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(3, '2003/2004', '2003-07-01', '2004-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(4, '2004/2005', '2004-07-01', '2005-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(5, '2005/2006', '2005-07-01', '2006-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(6, '2006/2007', '2006-07-01', '2007-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(7, '2007/2008', '2007-07-01', '2008-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(8, '2008/2009', '2008-07-01', '2009-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(9, '2009/2010', '2009-07-01', '2010-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(10, '2010/2011', '2010-07-01', '2011-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(11, '2011/2012', '2011-07-01', '2012-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(12, '2012/2013', '2012-07-01', '2013-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(13, '2013/2014', '2013-07-01', '2014-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(14, '2014/2015', '2014-07-01', '2015-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(15, '2015/2016', '2015-07-01', '2016-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(16, '2016/2017', '2016-07-01', '2017-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(17, '2017/2018', '2017-07-01', '2018-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(18, '2018/2019', '2018-07-01', '2019-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(19, '2019/2020', '2019-07-01', '2020-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(20, '2020/2021', '2020-07-01', '2021-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(21, '2021/2022', '2021-07-01', '2022-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(22, '2022/2023', '2022-07-01', '2023-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(23, '2023/2024', '2023-07-01', '2024-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(24, '2024/2025', '2024-07-01', '2025-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(25, '2025/2026', '2025-07-01', '2026-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(26, '2026/2027', '2026-07-01', '2027-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(27, '2027/2028', '2027-07-01', '2028-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(28, '2028/2029', '2028-07-01', '2029-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(29, '2029/2030', '2029-07-01', '2030-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, '2030/2031', '2030-07-01', '2031-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(31, '2031/2032', '2031-07-01', '2032-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(32, '2032/2033', '2032-07-01', '2033-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(33, '2033/2034', '2033-07-01', '2034-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(34, '2034/2035', '2034-07-01', '2035-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(35, '2035/2036', '2035-07-01', '2036-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(36, '2036/2037', '2036-07-01', '2037-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(37, '2037/2038', '2037-07-01', '2038-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(38, '2038/2039', '2038-07-01', '2039-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(39, '2039/2040', '2039-07-01', '2040-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(40, '2040/2041', '2040-07-01', '2041-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(41, '2041/2042', '2041-07-01', '2042-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(42, '2042/2043', '2042-07-01', '2043-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(43, '2043/2044', '2043-07-01', '2044-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(44, '2044/2045', '2044-07-01', '2045-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(45, '2045/2046', '2045-07-01', '2046-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(46, '2046/2047', '2046-07-01', '2047-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(47, '2047/2048', '2047-07-01', '2048-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(48, '2048/2049', '2048-07-01', '2049-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(49, '2049/2050', '2049-07-01', '2050-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(50, '2050/2051', '2050-07-01', '2051-06-30', 1, '2026-01-01 01:00:00', '2026-01-01 01:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `t_users`
--

CREATE TABLE `t_users` (
  `id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_users`
--

INSERT INTO `t_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `created_at`, `update_at`) VALUES
(1, 0, 'user01@sekolah.test', '2026-01-01 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(2, 0, 'user02@sekolah.test', '2026-01-02 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-02 01:00:00', '2026-01-02 01:00:00'),
(3, 0, 'user03@sekolah.test', '2026-01-03 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-03 01:00:00', '2026-01-03 01:00:00'),
(4, 0, 'user04@sekolah.test', '2026-01-04 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-04 01:00:00', '2026-01-04 01:00:00'),
(5, 0, 'user05@sekolah.test', '2026-01-05 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-05 01:00:00', '2026-01-05 01:00:00'),
(6, 0, 'user06@sekolah.test', '2026-01-06 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-06 01:00:00', '2026-01-06 01:00:00'),
(7, 0, 'user07@sekolah.test', '2026-01-07 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-07 01:00:00', '2026-01-07 01:00:00'),
(8, 0, 'user08@sekolah.test', '2026-01-08 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-08 01:00:00', '2026-01-08 01:00:00'),
(9, 0, 'user09@sekolah.test', '2026-01-09 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-09 01:00:00', '2026-01-09 01:00:00'),
(10, 0, 'user10@sekolah.test', '2026-01-10 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-10 01:00:00', '2026-01-10 01:00:00'),
(11, 0, 'user11@sekolah.test', '2026-01-11 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-11 01:00:00', '2026-01-11 01:00:00'),
(12, 0, 'user12@sekolah.test', '2026-01-12 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-12 01:00:00', '2026-01-12 01:00:00'),
(13, 0, 'user13@sekolah.test', '2026-01-13 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-13 01:00:00', '2026-01-13 01:00:00'),
(14, 0, 'user14@sekolah.test', '2026-01-14 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-14 01:00:00', '2026-01-14 01:00:00'),
(15, 0, 'user15@sekolah.test', '2026-01-15 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-15 01:00:00', '2026-01-15 01:00:00'),
(16, 0, 'user16@sekolah.test', '2026-01-16 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-16 01:00:00', '2026-01-16 01:00:00'),
(17, 0, 'user17@sekolah.test', '2026-01-17 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-17 01:00:00', '2026-01-17 01:00:00'),
(18, 0, 'user18@sekolah.test', '2026-01-18 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-18 01:00:00', '2026-01-18 01:00:00'),
(19, 0, 'user19@sekolah.test', '2026-01-19 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-19 01:00:00', '2026-01-19 01:00:00'),
(20, 0, 'user20@sekolah.test', '2026-01-20 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-20 01:00:00', '2026-01-20 01:00:00'),
(21, 0, 'user21@sekolah.test', '2026-01-21 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-21 01:00:00', '2026-01-21 01:00:00'),
(22, 0, 'user22@sekolah.test', '2026-01-22 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-22 01:00:00', '2026-01-22 01:00:00'),
(23, 0, 'user23@sekolah.test', '2026-01-23 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-23 01:00:00', '2026-01-23 01:00:00'),
(24, 0, 'user24@sekolah.test', '2026-01-24 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-24 01:00:00', '2026-01-24 01:00:00'),
(25, 0, 'user25@sekolah.test', '2026-01-25 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-25 01:00:00', '2026-01-25 01:00:00'),
(26, 0, 'user26@sekolah.test', '2026-01-26 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-26 01:00:00', '2026-01-26 01:00:00'),
(27, 0, 'user27@sekolah.test', '2026-01-27 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-27 01:00:00', '2026-01-27 01:00:00'),
(28, 0, 'user28@sekolah.test', '2026-01-28 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-28 01:00:00', '2026-01-28 01:00:00'),
(29, 0, 'user29@sekolah.test', '2026-01-01 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-01 01:00:00', '2026-01-01 01:00:00'),
(30, 0, 'user30@sekolah.test', '2026-01-02 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-02 01:00:00', '2026-01-02 01:00:00'),
(31, 0, 'user31@sekolah.test', '2026-01-03 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-03 01:00:00', '2026-01-03 01:00:00'),
(32, 0, 'user32@sekolah.test', '2026-01-04 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-04 01:00:00', '2026-01-04 01:00:00'),
(33, 0, 'user33@sekolah.test', '2026-01-05 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-05 01:00:00', '2026-01-05 01:00:00'),
(34, 0, 'user34@sekolah.test', '2026-01-06 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-06 01:00:00', '2026-01-06 01:00:00'),
(35, 0, 'user35@sekolah.test', '2026-01-07 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-07 01:00:00', '2026-01-07 01:00:00'),
(36, 0, 'user36@sekolah.test', '2026-01-08 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-08 01:00:00', '2026-01-08 01:00:00'),
(37, 0, 'user37@sekolah.test', '2026-01-09 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-09 01:00:00', '2026-01-09 01:00:00'),
(38, 0, 'user38@sekolah.test', '2026-01-10 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-10 01:00:00', '2026-01-10 01:00:00'),
(39, 0, 'user39@sekolah.test', '2026-01-11 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-11 01:00:00', '2026-01-11 01:00:00'),
(40, 0, 'user40@sekolah.test', '2026-01-12 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-12 01:00:00', '2026-01-12 01:00:00'),
(41, 0, 'user41@sekolah.test', '2026-01-13 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-13 01:00:00', '2026-01-13 01:00:00'),
(42, 0, 'user42@sekolah.test', '2026-01-14 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-14 01:00:00', '2026-01-14 01:00:00'),
(43, 0, 'user43@sekolah.test', '2026-01-15 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-15 01:00:00', '2026-01-15 01:00:00'),
(44, 0, 'user44@sekolah.test', '2026-01-16 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-16 01:00:00', '2026-01-16 01:00:00'),
(45, 0, 'user45@sekolah.test', '2026-01-17 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-17 01:00:00', '2026-01-17 01:00:00'),
(46, 0, 'user46@sekolah.test', '2026-01-18 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-18 01:00:00', '2026-01-18 01:00:00'),
(47, 0, 'user47@sekolah.test', '2026-01-19 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'staff', '2026-01-19 01:00:00', '2026-01-19 01:00:00'),
(48, 0, 'user48@sekolah.test', '2026-01-20 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'operator', '2026-01-20 01:00:00', '2026-01-20 01:00:00'),
(49, 0, 'user49@sekolah.test', '2026-01-21 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'admin', '2026-01-21 01:00:00', '2026-01-21 01:00:00'),
(50, 0, 'user50@sekolah.test', '2026-01-22 01:00:00', '$2y$12$d.eyf42nZKaApRpcOw9U/.ZI4c3whSK7FHZEcZVmD98w7.Kuk5eZW', '', 'guru', '2026-01-22 01:00:00', '2026-01-22 01:00:00');

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
(1, 26, 1, 1, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(2, 26, 2, 2, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(3, 26, 3, 3, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(4, 26, 4, 4, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(5, 26, 5, 5, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
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
(27, 26, 27, 27, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(28, 26, 28, 28, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(29, 26, 29, 29, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(30, 26, 30, 30, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(31, 26, 31, 31, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
(32, 26, 32, 32, '2026-07-01', '2027-06-30', 1, '2026-07-01 01:00:00', '2026-07-01 01:00:00'),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_kelas`
--
ALTER TABLE `t_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_kelas_siswa`
--
ALTER TABLE `t_kelas_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_pelanggaran`
--
ALTER TABLE `t_pelanggaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_pelanggaran_kategori`
--
ALTER TABLE `t_pelanggaran_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_pelanggaran_siswa`
--
ALTER TABLE `t_pelanggaran_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_siswa`
--
ALTER TABLE `t_siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_tahun_ajaran`
--
ALTER TABLE `t_tahun_ajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `t_wali_kelas`
--
ALTER TABLE `t_wali_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

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
