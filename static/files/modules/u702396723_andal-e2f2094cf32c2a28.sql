-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 25, 2024 at 01:38 PM
-- Server version: 10.11.7-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hfxagjii_handal`
--

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `slug` varchar(512) NOT NULL,
  `photo` varchar(512) NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `detail` longtext NOT NULL,
  `contact` varchar(128) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `slug`, `photo`, `title`, `description`, `detail`, `contact`, `timestamp`, `status`) VALUES
(1, 'jasa-penyediaan-sistem-keamanan', 'cybersecurity.jpg', 'Jasa Penyediaan Sistem Keamanan', 'Menyediakan solusi keamanan terbaik untuk melindungi properti Anda', 'Kami menyediakan jasa profesional dalam menyediakan sistem keamanan yang handal dan terpercaya. Dengan menggunakan teknologi terkini dan tenaga ahli berpengalaman, kami memastikan properti Anda aman dari ancaman. Hubungi kami sekarang untuk konsultasi gratis.', '081234567890', 1684715521, 0),
(2, 'jasa-desain-interior-profesional', 'desaininterior.jpg', 'Jasa Desain Interior Profesional', 'Transformasikan ruangan Anda menjadi tempat yang indah dan nyaman', 'Kami adalah tim desainer interior profesional yang siap membantu Anda merancang dan mengubah ruangan menjadi tempat yang indah dan nyaman. Dengan perpaduan desain yang kreatif, fungsionalitas yang optimal, dan perhatian terhadap detail, kami menghadirkan ruangan impian Anda menjadi kenyataan. Hubungi kami sekarang untuk konsultasi gratis.', '081234567891', 1683734604, 1),
(3, 'jasa-event-organizer-profesional', 'eventorganizer.jpg', 'Jasa Event Organizer Profesional', 'Menghadirkan pengalaman event yang tak terlupakan', 'Kami adalah tim event organizer profesional yang berdedikasi untuk menghadirkan pengalaman event yang tak terlupakan. Dari perencanaan hingga pelaksanaan, kami menangani segala aspek event dengan cermat dan detail. Dengan kreativitas dan ketekunan, kami memastikan setiap acara menjadi sukses. Hubungi kami sekarang untuk konsultasi gratis.', '081234567892', 1684330860, 1),
(4, 'jasa-perawatan-taman-profesional', 'taman.jpg', 'Jasa Perawatan Taman Profesional', 'Menghidupkan keindahan alam dalam taman Anda', 'Kami adalah tim ahli perawatan taman yang siap membantu Anda merawat dan mempercantik taman Anda. Dengan pengetahuan yang mendalam tentang tanaman, desain taman yang estetis, dan perawatan yang berkualitas, kami menciptakan ruang hijau yang indah dan nyaman. Hubungi kami sekarang untuk konsultasi gratis.', '081234567893', 1684898086, 1),
(5, 'jasa-pemasangan-ac-profesional', 'teknisi-ac.jpg', 'Jasa Pemasangan AC Profesional', 'Nikmati udara sejuk dengan pemasangan AC yang terpercaya', 'Kami adalah tim ahli pemasangan AC yang siap membantu Anda memasang AC dengan profesional dan terpercaya. Dengan pengalaman bertahun-tahun dalam instalasi AC, kami memastikan pemasangan yang tepat, efisien, dan tahan lama. Nikmati udara sejuk dengan layanan kami. Hubungi kami sekarang untuk konsultasi gratis.', '081234567894', 1683267055, 1),
(6, 'jasa-pembuatan-website-profesional', 'webdev.jpg', 'Jasa Pembuatan Website Profesional', 'Menghadirkan website berkualitas untuk bisnis Anda', 'Kami adalah tim pengembang website profesional yang siap membantu Anda membangun website berkualitas tinggi untuk bisnis Anda. Dengan desain yang menarik, fungsionalitas yang optimal, dan kecepatan yang tinggi, kami menciptakan website yang memikat pengunjung dan menghasilkan hasil yang baik. Hubungi kami sekarang untuk konsultasi gratis.', '081234567895', 1684123815, 1),
(7, 'jasa-penyediaan-tenaga-kerja-profesional', '', 'Jasa Penyediaan Tenaga Kerja Profesional', 'Temukan tenaga kerja terbaik untuk kebutuhan bisnis Anda', 'Kami adalah agen penyedia tenaga kerja yang berdedikasi untuk membantu Anda menemukan tenaga kerja yang sesuai dengan kebutuhan bisnis Anda. Dengan jaringan yang luas dan proses seleksi yang ketat, kami memastikan Anda mendapatkan tenaga kerja yang berkualitas dan profesional. Hubungi kami sekarang untuk konsultasi gratis.', '081234567896', 1685265513, 0),
(8, 'jasa-perbaikan-elektronik-profesional', 'electronic.png', 'Jasa Perbaikan Elektronik Profesional', 'Perbaikan elektronik yang cepat dan handal', 'Kami adalah tim teknisi elektronik yang siap membantu Anda memperbaiki perangkat elektronik dengan cepat dan handal. Dari perbaikan komputer hingga perangkat rumah tangga, kami menangani berbagai masalah elektronik dengan keahlian dan pengalaman. Hubungi kami sekarang untuk konsultasi gratis.', '081234567897', 1683046923, 0),
(9, 'jasa-konsultasi-bisnis-profesional', 'konsultan-bisnis.jpg', 'Jasa Konsultasi Bisnis Profesional', 'Dapatkan strategi dan solusi bisnis yang efektif', 'Kami adalah tim konsultan bisnis yang siap membantu Anda mengembangkan strategi dan solusi bisnis yang efektif. Dengan pemahaman mendalam tentang industri dan pengalaman yang luas, kami memberikan wawasan berharga dan rekomendasi yang tepat untuk pertumbuhan bisnis Anda. Hubungi kami sekarang untuk konsultasi gratis.', '081234567898', 1684599274, 0),
(10, 'jasa-fotografi-profesional', 'photographer.jpeg', 'Jasa Fotografi Profesional', 'Merekam momen berharga dalam gambar yang indah', 'Kami adalah tim fotografer profesional yang siap membantu Anda mengabadikan momen berharga dalam gambar yang indah. Dari pemotretan pernikahan hingga acara bisnis, kami menciptakan karya seni fotografi yang memukau. Hubungi kami sekarang untuk konsultasi gratis.', '081234567899', 1682946405, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
