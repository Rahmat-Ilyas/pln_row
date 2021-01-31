-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 31 Jan 2021 pada 17.30
-- Versi server: 8.0.22-0ubuntu0.20.04.3
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
(4, '14021998', 'Rahmat Ilyas', '085333341194', 'Rahmat-Ilyas-1611855902.jpg', 'row1', '$2y$10$QgpXDnoMd9.HjTZs5xZEQeD/EIrKJzf9GX.0DRUwbVInmWcNPbuKe', 'new');

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
(1, '12117114021998', 'Rahmat Ilyas', 'admin', '$2y$10$QgpXDnoMd9.HjTZs5xZEQeD/EIrKJzf9GX.0DRUwbVInmWcNPbuKe');

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
  `lokasi` int NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `subpengerjaan_id` int NOT NULL,
  `rating` int NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_subpengerjaan`
--

CREATE TABLE `tb_subpengerjaan` (
  `id` int NOT NULL,
  `pengerjaan_id` int NOT NULL,
  `sasaran` varchar(255) NOT NULL,
  `total_kerusakan` int NOT NULL,
  `waktu_mulai` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `waktu_selesai` datetime NOT NULL,
  `durasi` varchar(255) NOT NULL,
  `foto_pengerjaan` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Indeks untuk tabel `tb_subpengerjaan`
--
ALTER TABLE `tb_subpengerjaan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_anggota`
--
ALTER TABLE `tb_anggota`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `tb_kepaladevisi`
--
ALTER TABLE `tb_kepaladevisi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_pengerjaan`
--
ALTER TABLE `tb_pengerjaan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_priode_laporan`
--
ALTER TABLE `tb_priode_laporan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_radius`
--
ALTER TABLE `tb_radius`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_rating`
--
ALTER TABLE `tb_rating`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_subpengerjaan`
--
ALTER TABLE `tb_subpengerjaan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
