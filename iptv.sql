-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Anamakine: localhost
-- Üretim Zamanı: 18 Mar 2021, 12:49:42
-- Sunucu sürümü: 5.7.33-0ubuntu0.16.04.1
-- PHP Sürümü: 7.0.33-0ubuntu0.16.04.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `iptv_data`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin_list`
--

CREATE TABLE `admin_list` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `admin_usrname` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `admin_passwd` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `admin_token` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `admin_yetki` varchar(255) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `admin_list`
--

INSERT INTO `admin_list` (`admin_id`, `admin_email`, `admin_usrname`, `admin_passwd`, `admin_token`, `admin_yetki`) VALUES
(1, 'alicangonullu@yahoo.com', 'alicangonullu', '5a6a73a13efd448510b6c4c41acda5ef890c3b7a', '5a6a73a13efd448510b6c4c41acda5ef890c3b7a', 'admin');
-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `iptv_config`
--

CREATE TABLE `iptv_config` (
  `config_id` int(11) NOT NULL,
  `logo` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `rtmp_port` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `ffmpeg_m3u8cfg` text COLLATE utf8_turkish_ci NOT NULL,
  `ffmpeg_ts` text COLLATE utf8_turkish_ci NOT NULL,
  `ffmpeg_flv` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `twitter_tkn` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `facebook_tkn` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `twitch_tkn` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `restream_tkn` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `youtube_tk` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `instagram_tk` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `sahip` varchar(255) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `iptv_config`
--

INSERT INTO `iptv_config` (`config_id`, `logo`, `rtmp_port`, `ffmpeg_m3u8cfg`, `ffmpeg_ts`, `ffmpeg_flv`, `twitter_tkn`, `facebook_tkn`, `twitch_tkn`, `restream_tkn`, `youtube_tk`, `instagram_tk`, `sahip`) VALUES
(1, 'https://metroui.org.ua/images/logo4.png', '1938', '-listen 1 -hls_wrap 8 -deinterlace -vcodec libx264 -pix_fmt yuv420p -preset ultrafast -r 30 -g 60 -b:v 2500k -acodec libmp3lame -ar 44100 -threads 6 -qscale 3 -b:a 712000 -bufsize 512k', '-c:v copy -c:a copy -t 00:05:00', '-deinterlace -vcodec libx264 -pix_fmt yuv420p -preset ultrafast -r 30 -g 60 -b:v 2500k -acodec libmp3lame -ar 44100 -threads 6 -qscale 3 -b:a 712000 -bufsize 512k', '123', '123', '123', '123', '123', '123', 'alicangonullu');
-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ip_block`
--

CREATE TABLE `ip_block` (
  `ip_id` int(11) NOT NULL,
  `ip_adress` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `ban_reason` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `ip_block_active` varchar(255) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `ip_block`
--

INSERT INTO `ip_block` (`ip_id`, `ip_adress`, `ban_reason`, `ip_block_active`) VALUES
(1, '::1', 'Nobody', '1');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ip_logger`
--

CREATE TABLE `ip_logger` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `browserinf` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `ip_logger`
--

INSERT INTO `ip_logger` (`id`, `ip`, `browserinf`, `date`) VALUES
(1, '192.241.223.219/', '{"Host":"185.114.23.199","User-Agent":"Mozilla\\/5.0 zgrab\\/0.x","Accept":"*\\/*","Accept-Encoding":"gzip"}', '2021-03-15 13:55:55');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `private_iptv`
--

CREATE TABLE `private_iptv` (
  `private_id` int(11) NOT NULL,
  `private_name` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `private_resim` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `private_iptv` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `private_active` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `private_sahip` varchar(255) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `private_iptv`
--

INSERT INTO `private_iptv` (`private_id`, `private_name`, `private_resim`, `private_iptv`, `private_active`, `private_sahip`) VALUES
(1, 'TRT1', '', 'https://tv-trt1.live.trt.com.tr/master_720.m3u8', '1', 'alicangonullu');
-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `public_iptv`
--

CREATE TABLE `public_iptv` (
  `public_id` int(11) NOT NULL,
  `public_name` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `public_tslink` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `video_stream` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `public_active` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `public_sahip` varchar(255) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `public_iptv`
--

INSERT INTO `public_iptv` (`public_id`, `public_name`, `public_tslink`, `video_stream`, `public_active`, `public_sahip`) VALUES
(1, 'a0046ad4c1bafc4ef04e41e755f28368', 'https://tv-trt1.live.trt.com.tr/master_720.m3u8', '0', '1', 'alicangonullu');
--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `admin_list`
--
ALTER TABLE `admin_list`
  ADD PRIMARY KEY (`admin_id`);

--
-- Tablo için indeksler `iptv_config`
--
ALTER TABLE `iptv_config`
  ADD PRIMARY KEY (`config_id`);

--
-- Tablo için indeksler `ip_block`
--
ALTER TABLE `ip_block`
  ADD PRIMARY KEY (`ip_id`);

--
-- Tablo için indeksler `ip_logger`
--
ALTER TABLE `ip_logger`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `private_iptv`
--
ALTER TABLE `private_iptv`
  ADD PRIMARY KEY (`private_id`);

--
-- Tablo için indeksler `public_iptv`
--
ALTER TABLE `public_iptv`
  ADD PRIMARY KEY (`public_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `admin_list`
--
ALTER TABLE `admin_list`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- Tablo için AUTO_INCREMENT değeri `iptv_config`
--
ALTER TABLE `iptv_config`
  MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- Tablo için AUTO_INCREMENT değeri `ip_block`
--
ALTER TABLE `ip_block`
  MODIFY `ip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- Tablo için AUTO_INCREMENT değeri `ip_logger`
--
ALTER TABLE `ip_logger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- Tablo için AUTO_INCREMENT değeri `private_iptv`
--
ALTER TABLE `private_iptv`
  MODIFY `private_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- Tablo için AUTO_INCREMENT değeri `public_iptv`
--
ALTER TABLE `public_iptv`
  MODIFY `public_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
