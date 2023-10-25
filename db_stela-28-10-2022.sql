-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2022 at 02:10 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_stela`
--

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_lampiran`
--

CREATE TABLE `dokumen_lampiran` (
  `id` int(11) NOT NULL,
  `id_tiket` int(11) NOT NULL,
  `original_name` varchar(100) NOT NULL,
  `image_name` varchar(100) NOT NULL,
  `image_type` varchar(100) NOT NULL,
  `image_size` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_input` varchar(100) NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `user_update` varchar(100) NOT NULL,
  `tanggal_update` datetime NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dokumen_lampiran`
--

INSERT INTO `dokumen_lampiran` (`id`, `id_tiket`, `original_name`, `image_name`, `image_type`, `image_size`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`, `keterangan`) VALUES
(1, 1, 'techno pertemuan 9', '2022-10-25 02-19-20-63578df8aee2e.png', 'image/png', 934804, 1, 'Gayuh', '2022-10-25 14:19:20', 'Gayuh', '2022-10-25 14:19:20', 'Gatau'),
(2, 1, 'techno pertemuan 10', '2022-10-25 02-19-20-63578df8b1d86.png', 'image/png', 934804, 1, 'Gayuh', '2022-10-25 14:19:20', 'Gayuh', '2022-10-25 14:19:20', 'Gatau'),
(3, 2, 'techno pertemuan 9', '2022-10-25 02-24-07-63578f17482a9.png', 'image/png', 934804, 1, 'Gayuh', '2022-10-25 14:24:07', 'Gayuh', '2022-10-25 14:24:07', 'Gatau'),
(4, 2, 'techno pertemuan 10', '2022-10-25 02-24-07-63578f174bd17.png', 'image/png', 934804, 1, 'Gayuh', '2022-10-25 14:24:07', 'Gayuh', '2022-10-25 14:24:07', 'Gatau'),
(5, 3, 'techno tgl 11 mlm', '2022-10-26 10-41-11-6358ac571de46.png', 'image/png', 359693, 1, 'Ahmad', '2022-10-26 10:41:11', 'Ahmad', '2022-10-26 10:41:11', 'Gatau'),
(6, 3, 'techno tgl 11 sore', '2022-10-26 10-41-11-6358ac5721af0.png', 'image/png', 215183, 1, 'Ahmad', '2022-10-26 10:41:11', 'Ahmad', '2022-10-26 10:41:11', 'Gatau'),
(7, 4, 'techno pertemuan 10', '2022-10-26 12-03-19-6358bf977b19e.png', 'image/png', 934804, 1, 'Gayuh', '2022-10-26 12:03:19', 'Gayuh', '2022-10-26 12:03:19', 'Gatau'),
(8, 4, 'techno tgl 11 mlm', '2022-10-26 12-03-19-6358bf977f2b1.png', 'image/png', 359693, 1, 'Gayuh', '2022-10-26 12:03:19', 'Gayuh', '2022-10-26 12:03:19', 'Gatau'),
(9, 5, 'techno pertemuan 10', '2022-10-26 12-08-00-6358c0b0abb85.png', 'image/png', 934804, 1, 'Gayuh', '2022-10-26 12:08:00', 'Gayuh', '2022-10-26 12:08:00', 'Gatau'),
(10, 5, 'techno tgl 11 mlm', '2022-10-26 12-08-00-6358c0b0af0c3.png', 'image/png', 359693, 1, 'Gayuh', '2022-10-26 12:08:00', 'Gayuh', '2022-10-26 12:08:00', 'Gatau'),
(11, 7, 'techno pertemuan 10', '2022-10-26 06-45-19-63591dcf62c1b.png', 'image/png', 934804, 1, 'Gayuh', '2022-10-26 18:45:19', 'Gayuh', '2022-10-26 18:45:19', 'Gatau'),
(12, 7, 'techno tgl 11 mlm', '2022-10-26 06-45-19-63591dcf66ce0.png', 'image/png', 359693, 1, 'Gayuh', '2022-10-26 18:45:19', 'Gayuh', '2022-10-26 18:45:19', 'Gatau'),
(13, 8, 'techno pertemuan 10', '2022-10-26 06-48-26-63591e8ae7225.png', 'image/png', 934804, 1, 'Gayuh', '2022-10-26 18:48:26', 'Gayuh', '2022-10-26 18:48:26', 'Gatau'),
(14, 8, 'techno tgl 11 mlm', '2022-10-26 06-48-26-63591e8aea4bd.png', 'image/png', 359693, 1, 'Gayuh', '2022-10-26 18:48:26', 'Gayuh', '2022-10-26 18:48:26', 'Gatau');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(100) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `kategori`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(1, 'Sistem Informasi', 1, NULL, NULL, NULL, NULL),
(2, 'Infrastruktur', 1, NULL, NULL, NULL, NULL),
(3, 'Tata Kelola', 1, NULL, NULL, NULL, NULL),
(4, 'Lainnya', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `list_peran`
--

CREATE TABLE `list_peran` (
  `id` int(11) NOT NULL,
  `nama_peran` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `list_peran`
--

INSERT INTO `list_peran` (`id`, `nama_peran`) VALUES
(1, 'admin'),
(2, 'verificator'),
(3, 'programmer'),
(4, 'viewer'),
(5, 'helpdesk'),
(6, 'servicedesk'),
(7, 'IT specialist');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `id_todo_list` int(11) DEFAULT NULL,
  `id_sub_kategori` int(11) DEFAULT NULL,
  `id_tiket` int(11) DEFAULT NULL,
  `pengguna` varchar(100) DEFAULT NULL,
  `keterangan` text,
  `tanggal_input` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `no_tiket` varchar(100) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `kd_departemen` varchar(10) DEFAULT NULL,
  `bagian` varchar(255) DEFAULT NULL,
  `gedung` varchar(255) DEFAULT NULL,
  `unit_kerja` varchar(255) DEFAULT NULL,
  `ruangan` varchar(255) DEFAULT NULL,
  `lantai` varchar(100) DEFAULT NULL,
  `telepon` varchar(100) DEFAULT NULL,
  `hp` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama_lengkap`, `username`, `email`, `password`, `status`, `kd_departemen`, `bagian`, `gedung`, `unit_kerja`, `ruangan`, `lantai`, `telepon`, `hp`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'user1', 'user1', 'user1@gmail.com', '24c9e15e52afc47c225b757e7bee1f9d', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Gayuh', 'asgr39', 'ahmadsgr39@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Ahmad', 'ahmd', 'ahmad39@gmail.com', '61243c7b9a4022cb3f8dc3106767ed12', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `peran`
--

CREATE TABLE `peran` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `id_peran` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `peran`
--

INSERT INTO `peran` (`id`, `id_pengguna`, `id_peran`, `status`) VALUES
(2, 2, 2, 1),
(3, 2, 5, 1),
(4, 3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `programmer`
--

CREATE TABLE `programmer` (
  `id` int(11) NOT NULL,
  `jabatan` enum('leader','programmer') NOT NULL,
  `id_tim_programmer` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `programmer`
--

INSERT INTO `programmer` (`id`, `jabatan`, `id_tim_programmer`, `id_pengguna`) VALUES
(1, 'leader', 4, 3),
(2, 'programmer', 4, 4),
(3, 'programmer', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `jumlah_tiket` int(11) NOT NULL,
  `rating_pekerjaan` float NOT NULL,
  `rating_kecepatan` float NOT NULL,
  `rating_total` float NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_input` varchar(100) NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `tanggal_update` datetime NOT NULL,
  `user_update` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `id_pengguna`, `jumlah_tiket`, `rating_pekerjaan`, `rating_kecepatan`, `rating_total`, `status`, `user_input`, `tanggal_input`, `tanggal_update`, `user_update`) VALUES
(1, 3, 10, 4, 5, 4.5, 1, 'Gayuh', '2022-10-28 00:00:00', '2022-10-28 00:00:00', 'Gayuh'),
(2, 4, 5, 4, 3, 3.5, 1, 'Gayuh', '2022-10-28 00:00:00', '2022-10-28 00:00:00', 'Gayuh');

-- --------------------------------------------------------

--
-- Table structure for table `status_tiket`
--

CREATE TABLE `status_tiket` (
  `id` int(11) NOT NULL,
  `status_tiket` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(100) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status_tiket`
--

INSERT INTO `status_tiket` (`id`, `status_tiket`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(1, 'Open', 1, NULL, NULL, NULL, NULL),
(2, 'OnHold', 1, NULL, NULL, NULL, NULL),
(3, 'OnHold(item not avalaible)', 1, NULL, NULL, NULL, NULL),
(4, 'Pending', 1, NULL, NULL, NULL, NULL),
(5, 'OnProcess', 1, NULL, NULL, NULL, NULL),
(6, 'Done', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_kategori`
--

CREATE TABLE `sub_kategori` (
  `id` int(11) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `sub_kategori` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(100) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_kategori`
--

INSERT INTO `sub_kategori` (`id`, `id_kategori`, `sub_kategori`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(1, 1, 'Singa Rusia', 1, NULL, NULL, NULL, NULL),
(2, 2, 'Internet & Jaringan', 1, NULL, NULL, NULL, NULL),
(3, NULL, 'Sistem Operasi', 1, NULL, NULL, NULL, NULL),
(4, NULL, 'Software', 1, NULL, NULL, NULL, NULL),
(5, NULL, 'Hardware', 1, NULL, NULL, NULL, NULL),
(6, NULL, 'Aplikasi', 1, NULL, NULL, NULL, NULL),
(7, NULL, 'Pengembangan Jaringan', 1, NULL, NULL, NULL, NULL),
(8, 4, 'Lain-Lain', 1, NULL, NULL, NULL, NULL),
(9, NULL, 'Teleconference', 1, NULL, NULL, NULL, NULL),
(10, NULL, 'File Sharing dan Cloud', 1, NULL, NULL, NULL, NULL),
(11, NULL, 'Undangan Rapat', 1, NULL, NULL, NULL, NULL),
(12, NULL, 'Masuk Ruang Server', 1, NULL, NULL, NULL, NULL),
(13, NULL, 'Insiden', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_tahapan`
--

CREATE TABLE `sub_tahapan` (
  `id` int(11) NOT NULL,
  `id_tahapan` int(11) NOT NULL,
  `id_tiket` int(11) NOT NULL,
  `sub_tahapan` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_input` varchar(255) NOT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_tahapan`
--

INSERT INTO `sub_tahapan` (`id`, `id_tahapan`, `id_tiket`, `sub_tahapan`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(2, 1, 1, 'Membuat kopi', 9, 'Gayuh', '2022-10-11 00:00:00', 'Human', '2022-10-13 15:11:44'),
(3, 1, 1, 'Buat buat', 1, 'Gayuh', '2022-10-11 11:37:20', 'Gayuh', '2022-10-11 11:37:20'),
(4, 2, 1, 'Buat lagi', 1, 'Yahya', '2022-10-11 11:42:17', 'Yahya', '2022-10-11 11:42:17'),
(6, 1, 1, 'Buka karung beras', 1, 'Yahya', '2022-10-11 11:47:29', 'Ahmad', '2022-10-14 13:57:16'),
(7, 2, 2, 'Masak Air', 1, 'Gayuh', '2022-10-12 12:45:05', 'Gayuh', '2022-10-12 12:45:05'),
(8, 3, 2, 'Makan Nasi', 1, 'Ahmad', '2022-10-12 11:28:38', 'Manusia', '2022-10-12 11:49:37'),
(9, 2, 2, 'Minum Air', 1, 'Ahmad', '2022-10-12 11:30:59', 'Solikhin', '2022-10-12 22:56:32'),
(10, 1, 2, 'Buat Angin', 1, 'Ahmad', '2022-10-12 12:03:27', 'Human', '2022-10-12 12:25:23'),
(11, 1, 4, 'Membuat Desain di Figma', 0, 'gayuh', '2022-10-15 14:05:18', 'Raharjo', '2022-10-15 14:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `tahapan`
--

CREATE TABLE `tahapan` (
  `id` int(11) NOT NULL,
  `tahapan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tahapan`
--

INSERT INTO `tahapan` (`id`, `tahapan`) VALUES
(1, 'Permohonan'),
(2, 'Perencanaan'),
(3, 'Perancangan'),
(4, 'Implementasi'),
(5, 'Pengujian'),
(6, 'Serah Terima');

-- --------------------------------------------------------

--
-- Table structure for table `tiket`
--

CREATE TABLE `tiket` (
  `id` int(11) NOT NULL,
  `no_tiket` varchar(100) NOT NULL,
  `id_via` int(11) NOT NULL,
  `id_pelapor` int(11) NOT NULL,
  `id_status_tiket` int(11) NOT NULL DEFAULT '1',
  `id_sub_kategori` int(11) DEFAULT NULL,
  `id_tim_programmer` int(11) DEFAULT NULL,
  `id_urgensi` int(11) DEFAULT NULL,
  `nama_pelapor` varchar(255) DEFAULT NULL,
  `bagian_pelapor` varchar(255) DEFAULT NULL,
  `gedung_pelapor` varchar(255) DEFAULT NULL,
  `unit_kerja_pelapor` varchar(255) DEFAULT NULL,
  `ruangan_pelapor` varchar(255) DEFAULT NULL,
  `lantai_pelapor` varchar(255) DEFAULT NULL,
  `telepon_pelapor` varchar(255) DEFAULT NULL,
  `hp_pelapor` varchar(255) DEFAULT NULL,
  `email_pelapor` varchar(255) DEFAULT NULL,
  `judul_permasalahan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `permasalahan_awal` text,
  `permasalahan_akhir` text,
  `solusi` text,
  `tanggal_pelaksanaan` date DEFAULT NULL,
  `rating_pekerjaan` int(11) DEFAULT NULL,
  `rating_kecepatan` int(11) DEFAULT NULL,
  `rating_total` float DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_input` varchar(100) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(100) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tiket`
--

INSERT INTO `tiket` (`id`, `no_tiket`, `id_via`, `id_pelapor`, `id_status_tiket`, `id_sub_kategori`, `id_tim_programmer`, `id_urgensi`, `nama_pelapor`, `bagian_pelapor`, `gedung_pelapor`, `unit_kerja_pelapor`, `ruangan_pelapor`, `lantai_pelapor`, `telepon_pelapor`, `hp_pelapor`, `email_pelapor`, `judul_permasalahan`, `keterangan`, `permasalahan_awal`, `permasalahan_akhir`, `solusi`, `tanggal_pelaksanaan`, `rating_pekerjaan`, `rating_kecepatan`, `rating_total`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(1, '63578df8ae1ca', 5, 1, 1, 1, 4, NULL, 'Gayuh', 'magang', 'nomaden', 'magang', 'nomaden', 'nomaden', '087873777429', '087873777429', 'ahmadsgr39@gmail.com', '', 'Gatau', 'Gatau', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Gayuh', '2022-10-25 14:19:20', 'Gayuh', '2022-10-25 14:19:20'),
(2, '63578f174286e', 5, 1, 1, 2, NULL, NULL, 'Gayuh', 'magang', 'nomaden', 'magang', 'nomaden', 'nomaden', '087873777429', '087873777429', 'ahmadsgr39@gmail.com', '', 'Gatau', 'Gatau', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Gayuh', '2022-10-25 14:24:07', 'Gayuh', '2022-10-25 14:24:07'),
(3, '6358ac571ca6a', 1, 1, 1, NULL, NULL, 1, 'Gayuh', 'magang', 'nomaden', 'magang', 'nomaden', 'nomaden', '087873777429', '087873777429', 'ahmadsgr39@gmail.com', '', 'Gatau', 'Gatau', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Ahmad', '2022-10-26 10:41:11', 'Ahmad', '2022-10-26 10:41:11'),
(4, '6358bf977a17c', 5, 1, 2, 2, NULL, NULL, 'Gayuh', 'magang', 'nomaden', 'magang', 'nomaden', 'nomaden', '087873777429', '087873777429', 'ahmadsgr39@gmail.com', 'Coba Doang', 'Gatau', NULL, NULL, NULL, '2022-10-28', NULL, NULL, NULL, 1, 'Gayuh', '2022-10-26 12:03:19', 'Gayuh', '2022-10-28 17:16:56'),
(5, '6358c0b0a7ed0', 5, 1, 1, NULL, NULL, NULL, 'Gayuh', 'magang', 'nomaden', 'magang', 'nomaden', 'nomaden', '087873777429', '087873777429', 'ahmadsgr39@gmail.com', 'Coba Doang Lagi', 'Gatau', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Gayuh', '2022-10-26 12:08:00', 'Gayuh', '2022-10-26 12:08:00'),
(6, '6358ac571ca6z', 1, 1, 1, NULL, NULL, NULL, 'Ahmad', 'Magang', 'Nusantara 1', 'PUSTEKINFO', 'Ruangan 1', '1', '087873777429', '087873777429', 'ahmadsgr39@gmail.com', 'Gabisa Internet', 'Jaringan ilang', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Ahmad', '2022-10-26 00:00:00', 'Ahmad', '2022-10-26 00:00:00'),
(7, '63591dcf5f313', 5, 1, 1, NULL, NULL, NULL, 'Gayuh', 'magang', 'nomaden', 'magang', 'nomaden', 'nomaden', '087873777429', '087873777429', 'ahmadsgr39@gmail.com', 'Coba Doang Lagi', 'Gatau', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Gayuh', '2022-10-26 18:45:19', 'Gayuh', '2022-10-26 18:45:19'),
(8, '63591e8ae5fc8', 5, 1, 1, 1, NULL, NULL, 'Gayuh', 'magang', 'Perpustakaan', 'magang', 'nomaden', 'nomaden', '087873777429', '087873777429', 'ahmadsgr39@gmail.com', 'Coba Doang Lagi', 'Gatau', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'Gayuh', '2022-10-26 18:48:26', 'Gayuh', '2022-10-26 18:48:26');

-- --------------------------------------------------------

--
-- Table structure for table `tiket_data`
--

CREATE TABLE `tiket_data` (
  `id` int(11) NOT NULL,
  `id_tiket` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `jumlah_keluar` int(11) DEFAULT NULL,
  `sn` varchar(255) DEFAULT NULL,
  `tanggal_pemasangan` datetime DEFAULT NULL,
  `lokasi_pemasangan` varchar(255) DEFAULT NULL,
  `id_petugas` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(100) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tiket_image_laporan`
--

CREATE TABLE `tiket_image_laporan` (
  `id` int(11) NOT NULL,
  `id_tiket` int(11) NOT NULL,
  `image_name` varchar(100) NOT NULL,
  `image_type` varchar(100) NOT NULL,
  `image_size` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_input` varchar(100) NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `user_update` varchar(100) NOT NULL,
  `tanggal_update` datetime NOT NULL,
  `original_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tiket_image_laporan`
--

INSERT INTO `tiket_image_laporan` (`id`, `id_tiket`, `image_name`, `image_type`, `image_size`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`, `original_name`) VALUES
(1, 1, 'Coba.jpg', 'JPG', 110, 1, 'Gayuh', '2022-10-28 00:00:00', 'Gayuh', '2022-10-28 00:00:00', 'coba'),
(2, 1, 'coba-lagi.png', 'PNG', 200, 1, 'Ahmad', '2022-10-28 00:00:00', 'Ahmad', '2022-10-28 00:00:00', 'coba-lagi.png');

-- --------------------------------------------------------

--
-- Table structure for table `tiket_petugas`
--

CREATE TABLE `tiket_petugas` (
  `id` int(11) NOT NULL,
  `id_tiket` int(11) DEFAULT NULL,
  `id_petugas` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `user_input` varchar(100) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(100) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tiket_petugas`
--

INSERT INTO `tiket_petugas` (`id`, `id_tiket`, `id_petugas`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(1, 2, 2, 1, 'Gayuh', '2022-10-24 00:00:00', 'Gayuh', '2022-10-24 00:00:00'),
(2, 2, 3, 1, 'Gayuh', '2022-10-24 00:00:00', 'Gayuh', '2022-10-24 00:00:00'),
(3, 6, 1, 1, 'Gayuh', '2022-10-26 00:00:00', 'Gayuh', '2022-10-26 00:00:00'),
(4, 6, 3, 1, 'Gayuh', '2022-10-26 00:00:00', 'Gayuh', '2022-10-26 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tim_programmer`
--

CREATE TABLE `tim_programmer` (
  `id` int(11) NOT NULL,
  `nama_tim` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tim_programmer`
--

INSERT INTO `tim_programmer` (`id`, `nama_tim`) VALUES
(4, 'Hoaammm');

-- --------------------------------------------------------

--
-- Table structure for table `todo_list`
--

CREATE TABLE `todo_list` (
  `id` int(11) NOT NULL,
  `id_sub_tahapan` int(11) DEFAULT NULL,
  `id_programmer` int(11) DEFAULT NULL,
  `todo_list` varchar(100) DEFAULT NULL,
  `deskripsi` text,
  `user_input` varchar(255) NOT NULL,
  `status_kerja` tinyint(1) NOT NULL DEFAULT '1',
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `todo_list`
--

INSERT INTO `todo_list` (`id`, `id_sub_tahapan`, `id_programmer`, `todo_list`, `deskripsi`, `user_input`, `status_kerja`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(2, 2, 3, 'Membuka Pintu', NULL, 'Gayuh', 1, '2022-10-13 00:00:00', 'Gayuh', '2022-10-11 00:00:00'),
(3, 2, 3, 'Mendorong Pintu', NULL, 'Gayuh', 1, '2022-10-14 00:00:00', 'Gayuh', '2022-10-14 00:00:00'),
(4, 7, 1, 'Nyalakan Kompor', NULL, 'Gayuh', 1, '2022-10-14 00:00:00', 'Gayuh', '2022-10-14 00:00:00'),
(5, 10, 2, 'Tiup', '', 'Gayuh', 2, '2022-10-14 00:00:00', 'Solikhin', '2022-10-14 15:36:18'),
(6, 8, 1, 'Buka karung beras', '0', 'Gayuh', 2, '2022-10-14 12:13:23', 'Solikhin', '2022-10-14 15:25:58'),
(7, 5, 1, 'tidur', NULL, 'Gayuh', 0, '2022-10-14 15:56:36', 'Gayuh', '2022-10-14 16:07:12'),
(8, 11, 1, 'Desain Home Page Figma', 'Warna Terlalu Gelap', 'Gayuh', 9, '2022-10-15 14:22:50', 'Human', '2022-10-15 14:31:10');

-- --------------------------------------------------------

--
-- Table structure for table `todo_list_dokumen`
--

CREATE TABLE `todo_list_dokumen` (
  `id` int(11) NOT NULL,
  `id_todo_list` int(11) DEFAULT NULL,
  `original_name` varchar(100) NOT NULL,
  `dokumen_name` varchar(100) NOT NULL,
  `dokumen_type` varchar(100) NOT NULL,
  `dokumen_size` int(11) NOT NULL,
  `status` tinyint(11) NOT NULL DEFAULT '1',
  `user_input` varchar(255) NOT NULL,
  `tanggal_input` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `todo_list_dokumen`
--

INSERT INTO `todo_list_dokumen` (`id`, `id_todo_list`, `original_name`, `dokumen_name`, `dokumen_type`, `dokumen_size`, `status`, `user_input`, `tanggal_input`) VALUES
(21, 2, 'biru', 'biru-2022-10-19 10-46-08.png', 'image/png', 8945, 1, 'Gayuh', '2022-10-19 10:46:08'),
(22, 2, '', 'rumput-2022-10-19 10-46-32.png', 'image/png', 23803, 1, 'Gayuh', '2022-10-19 10:46:32'),
(23, 7, 'tujuh', 'gas-2022-10-19 11-09-15.png', 'image/png', 33922, 1, 'Gayuh', '2022-10-19 11:09:15'),
(24, 0, 'rumput', '2022-10-24 10-52-54-63560c16166d7.png', 'image/png', 23803, 1, 'Solikhin', '2022-10-24 10:52:54'),
(25, 2, 'rumput', '2022-10-24 10-53-36-63560c40a18ae.png', 'image/png', 23803, 1, 'Solikhin', '2022-10-24 10:53:36'),
(26, 2, 'Mid Test Inggris', '2022-10-24 10-56-24-63560ce89bb8b.pdf', 'application/pdf', 10423, 1, 'Solikhin', '2022-10-24 10:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `urgensi`
--

CREATE TABLE `urgensi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `STATUS` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `urgensi`
--

INSERT INTO `urgensi` (`id`, `nama`, `STATUS`) VALUES
(1, 'Critical', 1),
(2, 'High', 1),
(3, 'Normal', 1),
(4, 'Low', 1);

-- --------------------------------------------------------

--
-- Table structure for table `via`
--

CREATE TABLE `via` (
  `id` int(11) NOT NULL,
  `via` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `user_input` varchar(100) NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `user_update` varchar(100) NOT NULL,
  `tanggal_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `via`
--

INSERT INTO `via` (`id`, `via`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(1, 'Nota Dinas', 1, 'angga', '2022-10-07 13:22:50', 'angga', '2022-10-07 13:22:50'),
(2, 'Telepon', 1, 'angga', '2022-10-07 13:22:50', 'angga', '2022-10-07 13:22:50'),
(3, 'Whatsapp', 1, 'angga', '2022-10-07 13:22:50', 'angga', '2022-10-07 13:22:50'),
(4, 'Langsung', 1, 'angga', '2022-10-07 13:22:50', 'angga', '2022-10-07 13:22:50'),
(5, 'Online', 1, 'angga', '2022-10-07 13:22:50', 'angga', '2022-10-07 13:22:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokumen_lampiran`
--
ALTER TABLE `dokumen_lampiran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_peran`
--
ALTER TABLE `list_peran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peran`
--
ALTER TABLE `peran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programmer`
--
ALTER TABLE `programmer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_tiket`
--
ALTER TABLE `status_tiket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_kategori`
--
ALTER TABLE `sub_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_tahapan`
--
ALTER TABLE `sub_tahapan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tahapan`
--
ALTER TABLE `tahapan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tiket`
--
ALTER TABLE `tiket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_tiket_uniq` (`no_tiket`);

--
-- Indexes for table `tiket_data`
--
ALTER TABLE `tiket_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tiket_image_laporan`
--
ALTER TABLE `tiket_image_laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tiket_petugas`
--
ALTER TABLE `tiket_petugas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tim_programmer`
--
ALTER TABLE `tim_programmer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todo_list`
--
ALTER TABLE `todo_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todo_list_dokumen`
--
ALTER TABLE `todo_list_dokumen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `urgensi`
--
ALTER TABLE `urgensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `via`
--
ALTER TABLE `via`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokumen_lampiran`
--
ALTER TABLE `dokumen_lampiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `list_peran`
--
ALTER TABLE `list_peran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `peran`
--
ALTER TABLE `peran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `programmer`
--
ALTER TABLE `programmer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `status_tiket`
--
ALTER TABLE `status_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sub_kategori`
--
ALTER TABLE `sub_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sub_tahapan`
--
ALTER TABLE `sub_tahapan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tahapan`
--
ALTER TABLE `tahapan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tiket_data`
--
ALTER TABLE `tiket_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiket_image_laporan`
--
ALTER TABLE `tiket_image_laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tiket_petugas`
--
ALTER TABLE `tiket_petugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tim_programmer`
--
ALTER TABLE `tim_programmer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `todo_list`
--
ALTER TABLE `todo_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `todo_list_dokumen`
--
ALTER TABLE `todo_list_dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `urgensi`
--
ALTER TABLE `urgensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `via`
--
ALTER TABLE `via`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
