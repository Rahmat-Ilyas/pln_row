-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 25 Feb 2021 pada 04.49
-- Versi server: 8.0.23-0ubuntu0.20.04.1
-- Versi PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_monitoring`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_anggota`
--

CREATE TABLE `tb_anggota` (
  `id` int NOT NULL,
  `nip` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_anggota`
--

INSERT INTO `tb_anggota` (`id`, `nip`, `nama`, `telepon`, `foto`, `username`, `password`, `status`) VALUES
(4, '14021998', 'Rahmat Ilyas', '085333341194', 'Rahmat-Ilyas-1613674553.png', 'row1', '$2y$10$q9fd1O7RNBeK0qb8oC.ltekfPW.syhYWmngpJI7yKslLUzZKghLC.', 'active'),
(17, '0877665', 'Namura Mikata', '085345778998', 'Namura-Mikata-1612450807.jpg', 'row2', '$2y$10$cOSXIC4Dv7qS3STUY6AmwOB.OQSMUFyvENvL7rL9BuGPh60CRQfhm', 'active'),
(18, '98689666', 'Reza Maulana', '085445322772', 'Reza-Maulana-1614093616.png', 'row3', '$2y$10$ZZwoTLeUAxbIm/KXb0j5Te3SUpP5uxw1Kf/KhPPQRQQTCYA117086', 'active'),
(19, '77564533', 'Makaya Ratasi', '085443224556', 'Makaya-Ratasi-1614193494.png', 'row4', '$2y$10$gGTvXbdWIstsT2/fYf0/aergcNH5CG99wLQOVdEvMHolm5H6SnB12', 'new');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kegiatan`
--

CREATE TABLE `tb_kegiatan` (
  `id` int NOT NULL,
  `pengerjaan_id` int NOT NULL,
  `sasaran` varchar(255) NOT NULL,
  `total_kerusakan` int DEFAULT NULL,
  `waktu_mulai` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `waktu_selesai` datetime DEFAULT NULL,
  `durasi` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `foto_kegiatan` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_kegiatan`
--

INSERT INTO `tb_kegiatan` (`id`, `pengerjaan_id`, `sasaran`, `total_kerusakan`, `waktu_mulai`, `waktu_selesai`, `durasi`, `foto_kegiatan`, `status`) VALUES
(3, 2, 'Usmen', 4, '2021-02-08 23:19:00', '2021-02-15 16:06:29', '13.29', 'foto_kegiatan-0008.png', 'accept'),
(4, 2, 'Kabel lunak mau putus', 0, '2021-02-11 17:36:00', NULL, NULL, NULL, 'refuse'),
(5, 1, 'Melakukan pengintaian di luar dinding', 2, '2021-02-12 22:04:43', '2021-02-15 16:06:29', '13.29', 'foto_kegiatan-0008.png', 'accept'),
(8, 5, 'Cabut rumput liar', 4, '2021-02-15 15:53:00', '2021-02-15 16:06:29', '13.29', 'foto_kegiatan-0008.png', 'accept'),
(25, 10, 'Besi Berkarat', 2, '2021-02-24 01:01:00', '2021-02-24 01:07:44', '6.44', 'foto_kegiatan-0025.png', 'accept'),
(26, 10, 'Ruangan Alat', 3, '2021-02-24 01:08:00', '2021-02-25 02:24:19', '16.19', 'foto_kegiatan-0026.png', 'accept'),
(27, 11, 'Pemeriksaan tangga berkarat', 1, '2021-02-25 02:33:00', '2021-02-25 02:34:43', '1.43', 'foto_kegiatan-0027.png', 'accept'),
(28, 11, 'Pemeliharaan tangga', 4, '2021-02-25 02:34:00', '2021-02-25 02:34:43', '0.43', 'foto_kegiatan-0028.png', 'accept'),
(29, 12, 'Tower span pertama', 6, '2021-02-25 03:44:00', '2021-02-25 03:45:09', '1.9', 'foto_kegiatan-0029.png', 'accept');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kepaladevisi`
--

CREATE TABLE `tb_kepaladevisi` (
  `id` int NOT NULL,
  `nip` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_kepaladevisi`
--

INSERT INTO `tb_kepaladevisi` (`id`, `nip`, `nama`, `username`, `password`) VALUES
(1, '12117114021998', 'Rahmat Ilyas', 'admin', '$2y$10$3.rGvRtGgva.eNKbZCTttu0tX7cWaomKw/oVac7w70xR0Lnx2N4Im');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pengerjaan`
--

CREATE TABLE `tb_pengerjaan` (
  `id` int NOT NULL,
  `anggota_id` int NOT NULL,
  `unit` varchar(255) NOT NULL,
  `gardu_induk` varchar(255) NOT NULL,
  `formulir` varchar(255) NOT NULL,
  `nomor_tiang` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `tggl_mulai` datetime NOT NULL,
  `tggl_selesai` datetime NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_pengerjaan`
--

INSERT INTO `tb_pengerjaan` (`id`, `anggota_id`, `unit`, `gardu_induk`, `formulir`, `nomor_tiang`, `keterangan`, `tggl_mulai`, `tggl_selesai`, `lokasi`, `latitude`, `longitude`) VALUES
(1, 4, 'UPT SISTEM MAKASSAR', 'GARDU UTP MAKASSAR', 'Tiang', '2', 'Bengkok', '2021-02-01 00:00:00', '2021-02-13 00:00:00', 'Jl. Dampang', '-5.094479736352009', '119.47065139905814'),
(2, 4, 'UPT SISTEM MAKASSAR', 'GARDU UTP MAKASSAR', 'Kabel', '20', 'Kabel Putus', '2021-02-06 00:00:00', '2021-02-11 19:23:00', 'Jl. Poros Makassar - Maros', '-5.1424088640368035', '119.45677400620637'),
(3, 17, 'UPT SISTEM MAKASSAR', 'GARDU UTP MAKASSAR', 'Tangga', '15', 'Tangga rusak', '2021-02-06 00:00:00', '2021-02-07 00:00:00', 'Jl. Dangko', '-5.181901807397107', '119.41480279954133'),
(5, 4, 'UPT SISTEM MAKASSAR', 'GARDU UTP MAKASSAR', 'Halama Tower', '12', 'Halaman tower dibersihkan setiap 2 minggu', '2021-02-14 00:00:00', '2021-02-15 00:00:00', 'Jl. Metro, Tj. Bunga', '-5.156193139862241', '119.40375289296466'),
(10, 4, 'UPT SISTEM MAKASSAR', 'GARDU UTP MAKASSAR', 'Halama Tower', '12', 'Halaman tower harus selalu bersih', '2021-02-24 00:00:00', '2021-02-25 00:00:00', 'Jl. Dangko', '-5.181089747858429', '119.41300706060586'),
(11, 18, 'UPT SISTEM MAKASSAR', 'GARDU UTP MAKASSAR', 'Tangga Panjat', '2', 'Tangga panjat adalah tangga untuk memanjat tower', '2021-02-25 00:00:00', '2021-02-25 00:00:00', 'Jl. Batua Raya', '-5.153568713112722', '119.45966250470768'),
(12, 18, 'UPT SISTEM MAKASSAR', 'GARDU UTP MAKASSAR', 'Towe dan Span', '3', 'Tower dan Sapn adalah tower dan span', '2021-02-25 00:00:00', '2021-03-04 00:00:00', 'Jl/ Sulttan Alauddin', '-5.177114915063822', '119.43849206956087');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_priode_laporan`
--

CREATE TABLE `tb_priode_laporan` (
  `id` int NOT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_akhir` datetime NOT NULL,
  `tanggal_stor` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_priode_laporan`
--

INSERT INTO `tb_priode_laporan` (`id`, `tanggal_mulai`, `tanggal_akhir`, `tanggal_stor`) VALUES
(1, '2020-12-10 00:15:50', '2021-01-11 00:15:50', '2021-01-12 00:15:50'),
(2, '2021-01-12 04:19:33', '2021-02-13 23:06:23', '2021-02-13 04:19:33'),
(3, '2021-02-16 00:00:00', '2021-03-17 00:00:00', '2021-03-17 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_radius`
--

CREATE TABLE `tb_radius` (
  `id` int NOT NULL,
  `lokasi_center` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `luas_daerah` float NOT NULL,
  `radius` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_radius`
--

INSERT INTO `tb_radius` (`id`, `lokasi_center`, `latitude`, `longitude`, `luas_daerah`, `radius`) VALUES
(1, 'Makassar City', '-5.143776626080299', '119.43434202166257', 198.96, 7.96);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rating`
--

CREATE TABLE `tb_rating` (
  `id` int NOT NULL,
  `kegiatan_id` int NOT NULL,
  `rating` int NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_rating`
--

INSERT INTO `tb_rating` (`id`, `kegiatan_id`, `rating`, `keterangan`) VALUES
(1, 2, 3, 'Kerja bagus'),
(2, 10, 5, 'Kerja Bagus'),
(3, 9, 4, 'Terlalu lama pengerjaannya'),
(4, 8, 5, 'Pengerjaan sangat bagus'),
(5, 7, 5, 'Data lama, belum di proses'),
(6, 11, 5, 'Pengerjaan sangat bagus'),
(7, 1, 0, 'Lama Sekali'),
(8, 5, 0, 'Ndak ada beres'),
(9, 3, 0, 'Tertolak'),
(10, 4, 0, 'Ngak Guna'),
(11, 15, 0, 'Ndak Bagus ki'),
(12, 16, 5, 'Pengerjaan sangat bagus'),
(13, 25, 5, 'Pengerjaan sangat bagus'),
(14, 26, 5, 'Pengerjaan sangat bagus'),
(15, 28, 5, 'Pengerjaan sangat bagus'),
(16, 27, 5, 'Pengerjaan sangat bagus'),
(17, 29, 4, 'Pengerjaan bagus');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_anggota`
--
ALTER TABLE `tb_anggota`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- Indeks untuk tabel `tb_kegiatan`
--
ALTER TABLE `tb_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_kepaladevisi`
--
ALTER TABLE `tb_kepaladevisi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_pengerjaan`
--
ALTER TABLE `tb_pengerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_priode_laporan`
--
ALTER TABLE `tb_priode_laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_radius`
--
ALTER TABLE `tb_radius`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_rating`
--
ALTER TABLE `tb_rating`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_anggota`
--
ALTER TABLE `tb_anggota`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `tb_kegiatan`
--
ALTER TABLE `tb_kegiatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `tb_kepaladevisi`
--
ALTER TABLE `tb_kepaladevisi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_pengerjaan`
--
ALTER TABLE `tb_pengerjaan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `tb_priode_laporan`
--
ALTER TABLE `tb_priode_laporan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_radius`
--
ALTER TABLE `tb_radius`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_rating`
--
ALTER TABLE `tb_rating`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
