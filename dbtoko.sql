-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2026 at 04:49 PM
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
-- Database: `dbtoko`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`, `created_at`) VALUES
(1, 'Oversized Tee', 'Kaos dengan potongan oversized, cocok untuk gaya streetwear modern', '2026-07-02 14:44:58'),
(2, 'Graphic Tee', 'Kaos dengan grafis dan desain unik yang mencolok', '2026-07-02 14:44:58'),
(3, 'Plain Tee', 'Kaos polos berkualitas tinggi dengan berbagai pilihan warna', '2026-07-02 14:44:58'),
(4, 'Long Sleeve', 'Kaos lengan panjang untuk tampilan casual dan stylish', '2026-07-02 14:44:58'),
(5, 'Crop Tee', 'Kaos pendek cropped untuk gaya yang bold dan trendy', '2026-07-02 14:44:58');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_produk` varchar(200) NOT NULL,
  `harga` decimal(15,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `id_kategori`, `nama_produk`, `harga`, `stok`, `gambar`, `deskripsi`, `created_at`) VALUES
(1, 1, 'Velox Urban Oversized Black', 189000.00, 50, 'heaven.png', 'Kaos oversized premium dengan bahan cotton 220gsm. Cocok untuk tampilan streetwear sehari-hari. Tersedia dalam pilihan warna hitam elegan.', '2026-07-02 14:44:58'),
(2, 1, 'Velox Downtown Oversized White', 189000.00, 35, 'heaven.png', 'Kaos oversized warna putih bersih, bahan cotton combed 30s yang lembut dan nyaman dipakai seharian.', '2026-07-02 14:44:58'),
(3, 2, 'Velox Graffiti Graphic Tee', 225000.00, 40, 'heaven.png', 'Kaos graphic dengan motif graffiti art eksklusif. Bahan cotton 200gsm dengan sablon discharge berkualitas tinggi.', '2026-07-02 14:44:58'),
(4, 2, 'Velox Abstract Art Tee', 215000.00, 30, 'heaven.png', 'Desain abstract art yang unik dan artistik. Limited edition dengan sablon DTG full color yang tajam.', '2026-07-02 14:44:58'),
(5, 3, 'Velox Essential Black', 159000.00, 80, 'twoside.png', 'Kaos polos hitam premium dari bahan cotton combed 30s. Jahitan rapi dengan detail yang teliti.', '2026-07-02 14:44:58'),
(6, 3, 'Velox Essential White', 159000.00, 75, 'twoside.png', 'Kaos polos putih premium, basic staple yang wajib ada di lemari pakaian kamu.', '2026-07-02 14:44:58'),
(7, 4, 'Velox Long Sleeve Navy', 245000.00, 25, 'twoside.png', 'Kaos lengan panjang warna navy dengan bahan cotton tebal 240gsm. Nyaman dipakai di berbagai cuaca.', '2026-07-02 14:44:58'),
(8, 5, 'Velox Crop Essential', 175000.00, 45, 'twoside.png', 'Crop tee dengan potongan yang sempurna. Bahan ribbed cotton yang stretchy dan nyaman dipakai.', '2026-07-02 14:44:58');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `nama_lengkap`, `created_at`) VALUES
(1, 'admin', '$2y$10$fYiC6QgqNd0kCdO.XVRmqurqWuO01dNXzbM5xHO41SjmWwVyuKMrC', 'Administrator Velox Co', '2026-07-02 14:44:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produk_kategori` (`id_kategori`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
