-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2022 at 07:26 AM
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
  `image_name` varchar(100) NOT NULL,
  `image_type` varchar(100) NOT NULL,
  `image_size` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `user_input` varchar(100) NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `user_update` varchar(100) NOT NULL,
  `tanggal_update` datetime NOT NULL,
  `keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `kd_departemen` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama_lengkap`, `username`, `email`, `password`, `status`, `kd_departemen`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', 1, NULL),
(2, 'user1', 'user1', 'user1@gmail.com', '24c9e15e52afc47c225b757e7bee1f9d', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `peran`
--

CREATE TABLE `peran` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `id_peran` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `peran`
--

INSERT INTO `peran` (`id`, `id_pengguna`, `id_peran`, `status`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1),
(3, 2, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `petugas` varchar(255) NOT NULL,
  `jumlah_tiket` int(11) NOT NULL,
  `rating_pekerjaan` float NOT NULL,
  `rating_kecepatan` float NOT NULL,
  `rating_total` float NOT NULL,
  `status` tinyint(1) NOT NULL,
  `user_input` varchar(100) NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `tanggal_update` datetime NOT NULL,
  `user_update` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(2, NULL, 'Internet & Jaringan', 1, NULL, NULL, NULL, NULL),
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
  `id_tahapan` int(11) DEFAULT NULL,
  `id_tiket` int(11) NOT NULL,
  `sub_tahapan` varchar(100) DEFAULT NULL,
  `user_input` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_tahapan`
--

INSERT INTO `sub_tahapan` (`id`, `id_tahapan`, `id_tiket`, `sub_tahapan`, `user_input`, `status`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(1, 1, 1, 'Membuat Indomie', 'Gayuh', 1, '2022-10-11 00:00:00', 'Gayuh', '2022-10-11 00:00:00'),
(2, 1, 1, 'Membuat kopi', 'Gayuh', 1, '2022-10-11 00:00:00', 'Gayuh', '2022-10-11 00:00:00'),
(3, 1, 1, 'Buat buat', 'Gayuh', 1, '2022-10-11 11:37:20', 'Gayuh', '2022-10-11 11:37:20'),
(4, 1, 1, 'Buat lagi', 'Yahya', 1, '2022-10-11 11:42:17', 'Yahya', '2022-10-11 11:42:17'),
(5, 1, 1, 'Buat lagi', 'Yahya', 1, '2022-10-11 11:44:08', 'Yahya', '2022-10-11 11:44:08'),
(6, 1, 1, 'Buat lagi', 'Yahya', 1, '2022-10-11 11:47:29', 'Yahya', '2022-10-11 11:47:29');

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
  `tipe_pelapor` int(11) DEFAULT NULL,
  `id_pelapor` int(11) NOT NULL,
  `id_status_tiket` int(11) DEFAULT NULL,
  `id_sub_kategori` int(11) DEFAULT NULL,
  `nama_pelapor` varchar(255) DEFAULT NULL,
  `bagian_pelapor` varchar(255) DEFAULT NULL,
  `gedung_pelapor` varchar(255) DEFAULT NULL,
  `unit_kerja_pelapor` varchar(255) DEFAULT NULL,
  `ruangan_pelapor` varchar(255) DEFAULT NULL,
  `lantai_pelapor` varchar(255) DEFAULT NULL,
  `telepon_pelapor` varchar(255) DEFAULT NULL,
  `hp_pelapor` varchar(255) DEFAULT NULL,
  `email_pelapor` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `permasalahan_awal` text,
  `permasalahan_akhir` text,
  `solusi` text,
  `tanggal_pelaksanaan` date DEFAULT NULL,
  `rating_pekerjaan` int(11) DEFAULT NULL,
  `rating_kecepatan` int(11) DEFAULT NULL,
  `rating_total` float DEFAULT NULL,
  `tanggal_tiket` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(100) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tiket`
--

INSERT INTO `tiket` (`id`, `no_tiket`, `id_via`, `tipe_pelapor`, `id_pelapor`, `id_status_tiket`, `id_sub_kategori`, `nama_pelapor`, `bagian_pelapor`, `gedung_pelapor`, `unit_kerja_pelapor`, `ruangan_pelapor`, `lantai_pelapor`, `telepon_pelapor`, `hp_pelapor`, `email_pelapor`, `keterangan`, `permasalahan_awal`, `permasalahan_akhir`, `solusi`, `tanggal_pelaksanaan`, `rating_pekerjaan`, `rating_kecepatan`, `rating_total`, `tanggal_tiket`, `status`, `user_input`, `tanggal_input`, `user_update`, `tanggal_update`) VALUES
(1, '', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `status` tinyint(1) NOT NULL,
  `user_input` varchar(100) NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `user_update` varchar(100) NOT NULL,
  `tanggal_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tiket_petugas`
--

CREATE TABLE `tiket_petugas` (
  `id` int(11) NOT NULL,
  `id_tiket` int(11) DEFAULT NULL,
  `id_petugas` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(100) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `todo_list`
--

CREATE TABLE `todo_list` (
  `id` int(11) NOT NULL,
  `id_sub_tahapan` int(11) DEFAULT NULL,
  `todo_list` varchar(100) DEFAULT NULL,
  `user_input` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `todo_list_dokumen`
--

CREATE TABLE `todo_list_dokumen` (
  `id` int(11) NOT NULL,
  `id_todo_list` int(11) DEFAULT NULL,
  `dokumen_path` varchar(100) DEFAULT NULL,
  `user_input` varchar(255) NOT NULL,
  `tanggal_input` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `peran`
--
ALTER TABLE `peran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tahapan`
--
ALTER TABLE `tahapan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tiket`
--
ALTER TABLE `tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tiket_data`
--
ALTER TABLE `tiket_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiket_image_laporan`
--
ALTER TABLE `tiket_image_laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tiket_petugas`
--
ALTER TABLE `tiket_petugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `todo_list`
--
ALTER TABLE `todo_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `todo_list_dokumen`
--
ALTER TABLE `todo_list_dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `via`
--
ALTER TABLE `via`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
