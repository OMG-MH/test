-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 14, 2025 at 01:26 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `username`, `contact`, `address`, `email_verified_at`, `image`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Mr Admin', 'admin@example.com', 'admin', '+60  036253-1370', '477 Jalan Kelapa Hijau Segambut Bahagia', NULL, '6667e37a558631718084474.png', '$2y$12$ZGhZSJpa/CtjT6omKsFKr.HrsKBCE7EhfQx8JrPRrO6OrKvBdYqRS', NULL, NULL, '2024-07-14 04:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `click_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assigned_jobs`
--

CREATE TABLE `assigned_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `job_id` bigint UNSIGNED NOT NULL,
  `assigned_by` bigint UNSIGNED NOT NULL,
  `assigned_to` bigint UNSIGNED NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '2' COMMENT '1: completed, 2: in progress, 4: settled, 8: disputed',
  `disputant_id` bigint UNSIGNED DEFAULT NULL,
  `dispute_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `settled_author_amount` decimal(28,8) DEFAULT '0.00000000',
  `settled_freelancer_amount` decimal(28,8) DEFAULT '0.00000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `disputed_at` timestamp NULL DEFAULT NULL,
  `settled_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 -> Answered, 0 -> Unanswered',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `method_code` int UNSIGNED NOT NULL DEFAULT '0',
  `method_currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `btc_amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_wallet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_try` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 => success, 2 => pending, 3 => cancel',
  `from_api` tinyint(1) NOT NULL DEFAULT '0',
  `admin_feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_types`
--

CREATE TABLE `file_types` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: inactive, 1: active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `act`, `form_data`, `created_at`, `updated_at`) VALUES
(3, 'kyc', '{}', '2023-10-09 06:58:42', '2025-01-13 09:02:06'),
(11, 'kyf', '{}', '2024-10-13 06:58:42', '2025-01-14 01:43:36');

-- --------------------------------------------------------

--
-- Table structure for table `freelancer_reviews`
--

CREATE TABLE `freelancer_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `freelancer_id` bigint UNSIGNED NOT NULL,
  `job_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `review` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `code` int UNSIGNED DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1=>Active, 2=>Inactive',
  `gateway_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supported_currencies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `crypto` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `guideline` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `guideline`, `created_at`, `updated_at`) VALUES
(1, 0, 507, 'BTCPay', 'BTCPay', 1, '{\"store_id\":{\"title\":\"Store Id\",\"global\":true,\"value\":\"-----------------\"},\"api_key\":{\"title\":\"Api Key\",\"global\":true,\"value\":\"-----------------\"},\"server_name\":{\"title\":\"Server Name\",\"global\":true,\"value\":\"-----------------\"},\"secret_code\":{\"title\":\"Secret Code\",\"global\":true,\"value\":\"-----------------\"}}', '{\"BTC\":\"Bitcoin\",\"LTC\":\"Litecoin\"}', 1, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.BTCPay\"}}', NULL, NULL, '2025-01-14 06:00:59'),
(2, 0, 102, 'Perfect Money', 'PerfectMoney', 1, '{\"passphrase\":{\"title\":\"Alternate Passpharse\",\"global\":true,\"value\":\"-----------------\"},\"wallet_id\":{\"title\":\"PM Wallet\",\"global\":false,\"value\":\"\"}}', '{\"USD\":\"$\",\"EUR\":\"\\u20ac\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2025-01-14 06:02:57'),
(3, 0, 106, 'Payeer', 'Payeer', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"-----------------\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}', 0, '{\"status\":{\"title\": \"Status URL\",\"value\":\"ipn.Payeer\"}}', NULL, '2019-09-14 07:14:22', '2025-01-14 06:02:16'),
(4, 0, 107, 'PayStack', 'Paystack', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"-----\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----\"}}', '{\"USD\":\"USD\",\"NGN\":\"NGN\"}', 0, '{\"callback\":{\"title\": \"Callback URL\",\"value\":\"ipn.Paystack\"},\"webhook\":{\"title\": \"Webhook URL\",\"value\":\"ipn.Paystack\"}}\r\n', NULL, '2019-09-14 07:14:22', '2024-10-19 05:14:37'),
(5, 0, 109, 'Flutterwave', 'Flutterwave', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"-----\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----\"},\"encryption_key\":{\"title\":\"Encryption Key\",\"global\":true,\"value\":\"-----\"}}', '{\"BIF\":\"BIF\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CVE\":\"CVE\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"GHS\":\"GHS\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"KES\":\"KES\",\"LRD\":\"LRD\",\"MWK\":\"MWK\",\"MZN\":\"MZN\",\"NGN\":\"NGN\",\"RWF\":\"RWF\",\"SLL\":\"SLL\",\"STD\":\"STD\",\"TZS\":\"TZS\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"XAF\":\"XAF\",\"XOF\":\"XOF\",\"ZMK\":\"ZMK\",\"ZMW\":\"ZMW\",\"ZWD\":\"ZWD\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-10-17 11:24:09'),
(6, 0, 503, 'CoinPayments', 'Coinpayments', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"----------\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"----------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"----------\"}}', '{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}', 1, NULL, NULL, '2019-09-14 07:14:22', '2024-04-21 01:47:54'),
(7, 0, 506, 'Coinbase Commerce', 'CoinbaseCommerce', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"-----------------\"},\"secret\":{\"title\":\"Webhook Shared Secret\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"JPY\":\"JPY\",\"GBP\":\"GBP\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CNY\":\"CNY\",\"SEK\":\"SEK\",\"NZD\":\"NZD\",\"MXN\":\"MXN\",\"SGD\":\"SGD\",\"HKD\":\"HKD\",\"NOK\":\"NOK\",\"KRW\":\"KRW\",\"TRY\":\"TRY\",\"RUB\":\"RUB\",\"INR\":\"INR\",\"BRL\":\"BRL\",\"ZAR\":\"ZAR\",\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CDF\":\"CDF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NPR\":\"NPR\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}\r\n\r\n', 0, '{\"endpoint\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.CoinbaseCommerce\"}}', NULL, '2019-09-14 07:14:22', '2025-01-14 06:01:38'),
(8, 0, 113, 'Paypal Express', 'PaypalSdk', 1, '{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"-----------------\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"-----------------\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2025-01-14 06:02:30'),
(9, 0, 114, 'Stripe Checkout', 'StripeV3', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"-----------------\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, '{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}', NULL, '2019-09-14 07:14:22', '2025-01-14 06:03:40'),
(10, 0, 119, 'Mercado Pago', 'MercadoPago', 1, '{\"access_token\":{\"title\":\"Access Token\",\"global\":true,\"value\":\"-----\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-10-17 11:44:52'),
(11, 0, 120, 'Authorize.net', 'Authorize', 1, '{\"login_id\":{\"title\":\"Login ID\",\"global\":true,\"value\":\"-----------------\"},\"transaction_key\":{\"title\":\"Transaction Key\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2025-01-14 06:00:30'),
(12, 0, 509, 'Now Payments Checkout', 'NowPaymentsCheckout', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"----------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"----------\"}}', '{\"BTG\":\"BTG\",\"ETH\":\"ETH\",\"XMR\":\"XMR\",\"ZEC\":\"ZEC\",\"XVG\":\"XVG\",\"ADA\":\"ADA\",\"LTC\":\"LTC\",\"BCH\":\"BCH\",\"QTUM\":\"QTUM\",\"DASH\":\"DASH\",\"XLM\":\"XLM\",\"XRP\":\"XRP\",\"XEM\":\"XEM\",\"DGB\":\"DGB\",\"LSK\":\"LSK\",\"DOGE\":\"DOGE\",\"TRX\":\"TRX\",\"KMD\":\"KMD\",\"REP\":\"REP\",\"BAT\":\"BAT\",\"ARK\":\"ARK\",\"WAVES\":\"WAVES\",\"BNB\":\"BNB\",\"XZC\":\"XZC\",\"NANO\":\"NANO\",\"TUSD\":\"TUSD\",\"VET\":\"VET\",\"ZEN\":\"ZEN\",\"GRS\":\"GRS\",\"FUN\":\"FUN\",\"NEO\":\"NEO\",\"GAS\":\"GAS\",\"PAX\":\"PAX\",\"USDC\":\"USDC\",\"ONT\":\"ONT\",\"XTZ\":\"XTZ\",\"LINK\":\"LINK\",\"RVN\":\"RVN\",\"BNBMAINNET\":\"BNBMAINNET\",\"ZIL\":\"ZIL\",\"BCD\":\"BCD\",\"USDT\":\"USDT\",\"USDTERC20\":\"USDTERC20\",\"CRO\":\"CRO\",\"DAI\":\"DAI\",\"HT\":\"HT\",\"WABI\":\"WABI\",\"BUSD\":\"BUSD\",\"ALGO\":\"ALGO\",\"USDTTRC20\":\"USDTTRC20\",\"GT\":\"GT\",\"STPT\":\"STPT\",\"AVA\":\"AVA\",\"SXP\":\"SXP\",\"UNI\":\"UNI\",\"OKB\":\"OKB\",\"BTC\":\"BTC\"}', 1, '', NULL, NULL, '2024-04-21 01:46:31'),
(13, 0, 122, '2Checkout', 'TwoCheckout', 1, '{\"merchant_code\":{\"title\":\"Merchant Code\",\"global\":true,\"value\":\"-----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"}}', '{\"AFN\": \"AFN\",\"ALL\": \"ALL\",\"DZD\": \"DZD\",\"ARS\": \"ARS\",\"AUD\": \"AUD\",\"AZN\": \"AZN\",\"BSD\": \"BSD\",\"BDT\": \"BDT\",\"BBD\": \"BBD\",\"BZD\": \"BZD\",\"BMD\": \"BMD\",\"BOB\": \"BOB\",\"BWP\": \"BWP\",\"BRL\": \"BRL\",\"GBP\": \"GBP\",\"BND\": \"BND\",\"BGN\": \"BGN\",\"CAD\": \"CAD\",\"CLP\": \"CLP\",\"CNY\": \"CNY\",\"COP\": \"COP\",\"CRC\": \"CRC\",\"HRK\": \"HRK\",\"CZK\": \"CZK\",\"DKK\": \"DKK\",\"DOP\": \"DOP\",\"XCD\": \"XCD\",\"EGP\": \"EGP\",\"EUR\": \"EUR\",\"FJD\": \"FJD\",\"GTQ\": \"GTQ\",\"HKD\": \"HKD\",\"HNL\": \"HNL\",\"HUF\": \"HUF\",\"INR\": \"INR\",\"IDR\": \"IDR\",\"ILS\": \"ILS\",\"JMD\": \"JMD\",\"JPY\": \"JPY\",\"KZT\": \"KZT\",\"KES\": \"KES\",\"LAK\": \"LAK\",\"MMK\": \"MMK\",\"LBP\": \"LBP\",\"LRD\": \"LRD\",\"MOP\": \"MOP\",\"MYR\": \"MYR\",\"MVR\": \"MVR\",\"MRO\": \"MRO\",\"MUR\": \"MUR\",\"MXN\": \"MXN\",\"MAD\": \"MAD\",\"NPR\": \"NPR\",\"TWD\": \"TWD\",\"NZD\": \"NZD\",\"NIO\": \"NIO\",\"NOK\": \"NOK\",\"PKR\": \"PKR\",\"PGK\": \"PGK\",\"PEN\": \"PEN\",\"PHP\": \"PHP\",\"PLN\": \"PLN\",\"QAR\": \"QAR\",\"RON\": \"RON\",\"RUB\": \"RUB\",\"WST\": \"WST\",\"SAR\": \"SAR\",\"SCR\": \"SCR\",\"SGD\": \"SGD\",\"SBD\": \"SBD\",\"ZAR\": \"ZAR\",\"KRW\": \"KRW\",\"LKR\": \"LKR\",\"SEK\": \"SEK\",\"CHF\": \"CHF\",\"SYP\": \"SYP\",\"THB\": \"THB\",\"TOP\": \"TOP\",\"TTD\": \"TTD\",\"TRY\": \"TRY\",\"UAH\": \"UAH\",\"AED\": \"AED\",\"USD\": \"USD\",\"VUV\": \"VUV\",\"VND\": \"VND\",\"XOF\": \"XOF\",\"YER\": \"YER\"}', 1, '{\"approved_url\":{\"title\": \"Approved URL\",\"value\":\"ipn.TwoCheckout\"}}', NULL, NULL, '2025-01-14 06:03:56'),
(14, 0, 123, 'Checkout', 'Checkout', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"},\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"-----------------\"},\"processing_channel_id\":{\"title\":\"Processing Channel\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"AUD\":\"AUD\",\"CAN\":\"CAN\",\"CHF\":\"CHF\",\"SGD\":\"SGD\",\"JPY\":\"JPY\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2025-01-14 06:01:24'),
(15, 0, 110, 'RazorPay', 'Razorpay', 1, '{\"key_id\":{\"title\":\"Key Id\",\"global\":true,\"value\":\"-----------------\"},\"key_secret\":{\"title\":\"Key Secret \",\"global\":true,\"value\":\"-----------------\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, NULL, '2025-01-14 06:03:09'),
(16, 0, 111, 'Stripe Storefront', 'StripeJs', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"-----------------\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gateway_currencies`
--

CREATE TABLE `gateway_currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_code` int UNSIGNED DEFAULT NULL,
  `gateway_alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `gateway_parameter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `job_id` bigint UNSIGNED NOT NULL,
  `applicant_bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_proof` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '2' COMMENT '0: rejected, 1: approved, 2: pending',
  `author_notified` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: not notified, 1: notified',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_categories`
--

CREATE TABLE `job_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: inactive, 1: active',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: no, 1: yes',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_conversations`
--

CREATE TABLE `job_conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `assigned_job_id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_posts`
--

CREATE TABLE `job_posts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `subcategory_id` bigint UNSIGNED NOT NULL,
  `job_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `rate` decimal(28,8) NOT NULL,
  `total_budget` decimal(28,8) NOT NULL,
  `vacancy` int UNSIGNED NOT NULL,
  `has_job_proof` tinyint UNSIGNED NOT NULL COMMENT '1: optional, 2: required',
  `file_types` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '2' COMMENT '0: rejected, 1: approved, 2: pending, 3: paused, 4: unavailable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_subcategories`
--

CREATE TABLE `job_subcategories` (
  `id` bigint UNSIGNED NOT NULL,
  `job_category_id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: inactive, 1: active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `status`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, 1, NULL, '2023-09-17 11:48:33');

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subj` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shortcodes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  `sms_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subj`, `email_body`, `sms_body`, `shortcodes`, `email_status`, `sms_status`, `created_at`, `updated_at`) VALUES
(1, 'BAL_ADD', 'Balance - Added', 'Your Account has been Credited', '<p>{{amount}} {{site_currency}} has been added to your account.</p><p>Transaction Number : {{trx}}</p><p>Your Current Balance is :&nbsp;{{post_balance}}&nbsp; {{site_currency}}<br>&nbsp;</p><p>Admin note:&nbsp;<strong>{{remark}}</strong></p>', '{{amount}} {{site_currency}} credited in your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin note is \"{{remark}}\"', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 0, '2021-11-03 12:00:00', '2024-08-28 06:29:40'),
(2, 'BAL_SUB', 'Balance - Subtracted', 'Your Account has been Debited', '<div style=\"font-family: Montserrat, sans-serif;\">{{amount}} {{site_currency}} has been subtracted from your account .</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><span style=\"color: rgb(33, 37, 41); font-family: Montserrat, sans-serif;\">Your Current Balance is :&nbsp;</span><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">{{post_balance}}&nbsp; {{site_currency}}</span></font><br><div><font style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></font></div><div>Admin Note: {{remark}}</div>', '{{amount}} {{site_currency}} debited from your account. Your Current Balance {{post_balance}} {{site_currency}} . Transaction: #{{trx}}. Admin Note is {{remark}}', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '2021-11-03 12:00:00', '2022-04-03 02:24:11'),
(7, 'PASS_RESET_CODE', 'Password - Reset - Code', 'Password Reset', '<p>We have received a request to reset the password for your account on&nbsp;{{time}} .<br>&nbsp;</p><p>Requested From IP:&nbsp;{{ip}}&nbsp;using&nbsp;{{browser}}&nbsp;on&nbsp;{{operating_system}}&nbsp;.<br>&nbsp;</p><p>Your account recovery code is:&nbsp;&nbsp; {{code}}</p><p>If you do not wish to reset your password, please disregard this message.</p>', 'Your account recovery code is: {{code}}', '{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, 0, '2021-11-03 12:00:00', '2024-08-10 07:18:36'),
(8, 'PASS_RESET_DONE', 'Password - Reset - Confirmation', 'You have reset your password', '<p>You have successfully reset your password.</p><p>You changed from&nbsp; IP:&nbsp;{{ip}}&nbsp;using&nbsp;{{browser}}&nbsp;on&nbsp;{{operating_system}}&nbsp;&nbsp;on&nbsp;{{time}}<br>&nbsp;</p><p>If you did not change that, please contact us as soon as possible.</p>', 'Your password has been changed successfully.', '{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:18:54'),
(9, 'ADMIN_SUPPORT_REPLY', 'Support - Reply', 'Reply Support Ticket', '<div><p><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\">A member from our support team has replied to the following ticket:</span></span></p><p><span style=\"font-weight: bolder;\"><span data-mce-style=\"font-size: 11pt;\" style=\"font-size: 11pt;\"><span style=\"font-weight: bolder;\"><br></span></span></span></p><p><span style=\"font-weight: bolder;\">[Ticket#{{ticket_id}}] {{ticket_subject}}<br><br>Click here to reply:&nbsp; {{link}}</span></p><p>----------------------------------------------</p><p>Here is the reply :<br></p><p>{{reply}}<br></p></div><div><br style=\"font-family: Montserrat, sans-serif;\"></div>', 'Your Ticket#{{ticket_id}} :  {{ticket_subject}} has been replied.', '{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}', 1, 1, '2021-11-03 12:00:00', '2022-03-20 20:47:51'),
(10, 'EVER_CODE', 'Verification - Email', 'Please verify your email address', '<br><div><div style=\"font-family: Montserrat, sans-serif;\">Thanks For joining us.<br></div><div style=\"font-family: Montserrat, sans-serif;\">Please use the below code to verify your email address.<br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Your email verification code is:<font size=\"6\"><span style=\"font-weight: bolder;\">&nbsp;{{code}}</span></font></div></div>', '---', '{\"code\":\"Email verification code\"}', 1, 0, '2021-11-03 12:00:00', '2022-04-03 02:32:07'),
(11, 'SVER_CODE', 'Verification - SMS', 'Verify Your Mobile Number', '---', 'Your phone verification code is: {{code}}', '{\"code\":\"SMS Verification Code\"}', 0, 1, '2021-11-03 12:00:00', '2022-03-20 19:24:37'),
(12, 'WITHDRAW_APPROVE', 'Withdraw - Approved', 'Withdrawal Approval Notification', '<p>We are pleased to inform you that your withdrawal request has been approved by our administration team.<br>&nbsp;</p><p>Here are the details of your approved withdrawal:&nbsp;<br>&nbsp;</p><p><strong>Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Receivable Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Withdraw&nbsp;Via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Post Balance:</strong>&nbsp;{{post_balance}} {{site_currency}}</p><p>&nbsp;</p><p>{{admin_details}}<br>&nbsp;</p><p>Your withdrawal request has been processed successfully. If you have any further questions or require assistance, please feel free to contact us.<br>&nbsp;</p><p>Thank you for your patience and cooperation.<br>&nbsp;</p><p>Best&nbsp;regards,</p>', 'Good news! Your withdrawal request has been approved.\r\n\r\nDetails of your withdrawal:\r\n\r\nAmount: {{amount}} {{site_currency}}\r\nCharge: {{charge}} {{site_currency}}\r\nConversion Rate: 1 {{site_currency}} = {{rate}} {{method_currency}}\r\nReceivable Amount: {{method_amount}} {{method_currency}}\r\nWithdraw Via: {{method_name}}\r\nTransaction Number: {{trx}}\r\nPost Balance: {{post_balance}} {{site_currency}}\r\n\r\n{{admin_details}}\r\n\r\nFor further assistance, contact us anytime.', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\",\"admin_details\":\"Details provided by the admin\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:20:12'),
(13, 'WITHDRAW_REJECT', 'Withdraw - Rejected', 'Withdrawal Rejection Notification', '<p>We regret to inform you that your withdrawal request has been rejected by our administration team.<br>&nbsp;</p><p>Upon careful review, we found that there were certain discrepancies or issues that prevented us from processing your withdrawal at this time. We apologize for any inconvenience this may cause.<br>&nbsp;</p><p>Here are the details of your withdrawal:<br>&nbsp;</p><p><strong>Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>You should have received:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Withdraw&nbsp;Via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p>&nbsp;</p><p>{{admin_details}}<br>&nbsp;</p><p>{{amount}} {{site_currency}} has been refunded to your account, and your current balance is {{post_balance}} {{site_currency}}.</p><p>&nbsp;</p><p>If you have any questions or concerns regarding this decision, or if you believe there has been a misunderstanding, please don\'t hesitate to reach out to us. We are here to assist you and address any concerns you may have.</p><p>Thank you for your understanding.<br>&nbsp;</p><p>Best&nbsp;regards,</p>', 'We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.\r\n\r\nDetails of your withdrawal:\r\n\r\nAmount: {{amount}} {{site_currency}}\r\nCharge: {{charge}} {{site_currency}}\r\nConversion Rate: 1 {{site_currency}} = {{rate}} {{method_currency}}\r\nYou should have received: {{method_amount}} {{method_currency}}\r\nWithdraw Via: {{method_name}}\r\nTransaction Number: {{trx}}\r\n\r\n{{admin_details}}\r\n\r\n{{amount}} {{site_currency}} has been refunded to your account. Your current balance is {{post_balance}} {{site_currency}}.\r\n\r\nIf you have any questions, feel free to reach out.\r\n\r\nBest regards,', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:20:52'),
(14, 'WITHDRAW_REQUEST', 'Withdraw - Requested', 'Withdrawal Request Confirmation', '<p>We are pleased to inform you that your withdrawal request has been successfully submitted.<br>&nbsp;</p><p>Here are the details of your withdrawal:&nbsp;<br>&nbsp;</p><p><strong>Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Receivable Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Withdraw&nbsp;Via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Post Balance:</strong>&nbsp;{{post_balance}} {{site_currency}}</p><p>&nbsp;</p><p>If you have any questions or concerns regarding this transaction, please feel free to contact us. Thank you for choosing {{site_name}}.</p><p>Best&nbsp;regards,</p>', 'Your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been successfully submitted.\r\n\r\nDetails of your withdrawal:\r\n\r\nAmount: {{amount}} {{site_currency}}\r\nCharge: {{charge}} {{site_currency}}\r\nConversion Rate: 1 {{site_currency}} = {{rate}} {{method_currency}}\r\nReceivable Amount: {{method_amount}} {{method_currency}}\r\nWithdraw Via: {{method_name}}\r\nTransaction Number: {{trx}}\r\nPost Balance: {{post_balance}} {{site_currency}}\r\n\r\nFor any queries, reach out to us.', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:21:25'),
(15, 'DEFAULT', 'Default Template', '{{subject}}', '{{message}}', '{{message}}', '{\"subject\":\"Subject\",\"message\":\"Message\"}', 1, 0, '2019-09-14 13:14:22', '2024-02-18 08:37:31'),
(16, 'KYC_APPROVE', 'KYC Approved Successfully', 'KYC has been approved', NULL, NULL, '[]', 1, 1, NULL, NULL),
(17, 'KYC_REJECT', 'KYC Rejected Successfully', 'KYC has been rejected', NULL, NULL, '[]', 1, 1, NULL, NULL),
(24, 'JOB_REJECT', 'Job Rejected', 'Update on Your Job Post Submission', '<p>Thank you for submitting your job post titled <strong>\"{{title}}” </strong>(Job Code: {{job_code}}). After careful review, we regret to inform you that your job post has not been approved for publication on our platform.</p><p>&nbsp;</p><p>We understand that this may be disappointing. We encourage you to review the guidelines provided in our Privacy &amp; Policy page to better understand the requirements for job posts. You may revise and resubmit your job post for further consideration.</p><p>&nbsp;</p><p>If you have any questions or need assistance, feel free to reach out to us. We’re here to help you through this process and ensure your success on our platform.</p><p>&nbsp;</p><p>Thank you for your understanding and cooperation.</p><p>&nbsp;</p><p>Best regards,</p>', 'We\'re sorry, your job post \"{{title}}” (Job Code: {{job_code}}) was not approved. Please review our guidelines for more details.', '{\"job_code\":\"The unique identifier of the job\",\"title\":\"Title of the job\"}', 1, 1, '2021-11-03 12:00:00', '2024-09-19 05:42:12'),
(25, 'JOB_APPROVE', 'Job Approved', 'Your Job Post Has Been Approved! 🎉', '<p>We\'re excited to inform you that your job post titled <strong>\"{{title}}\"</strong> has been approved by our team!</p><p>&nbsp;</p><p>Job Details:</p><ul><li><strong>Job Code:</strong> {{job_code}}</li><li><strong>Job Cost:</strong> {{amount}} {{site_currency}}</li></ul><p>&nbsp;</p><p>Your job post is now live on our platform and available for potential freelancers to view and bid on. We wish you the best of luck in finding the perfect candidate to help you achieve your goals.</p><p>&nbsp;</p><p>If you have any questions or need assistance, feel free to contact our support team.</p><p>&nbsp;</p><p>Thank you for choosing us. We\'re here to support you every step of the way!</p><p>&nbsp;</p><p>Best regards,</p>', 'Great news! Your job post titled \"{{title}}\" (Job Code: {{job_code}}) has been approved and is now live. Check it out on {{site_name}}. Best of luck!', '{\"job_code\":\"The unique identifier of the job\",\"title\":\"Title of the job\",\"amount\":\"The costing amount for the job\"}', 1, 1, '2021-11-03 12:00:00', '2024-09-02 11:09:59'),
(29, 'DEPOSIT_COMPLETE', 'Deposit - Automated - Successful', 'Deposit Confirmation - Your Recent Deposit was Successful!', '<p>We are pleased to inform you that your recent deposit of {{amount}} {{site_currency}} has been successfully credited to your account. Thank you for choosing our services!</p><p>Here are the details of your transaction:</p><p>&nbsp;</p><p><strong>Deposit Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Received Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Paid&nbsp;via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Post Balance:&nbsp;</strong>{{post_balance}}&nbsp;{{site_currency}}<br>&nbsp;</p><p>You can view the updated balance and transaction history by logging into your account.</p><p>If you have any questions or need further assistance, please do not hesitate to contact our support team. We are here to help!<br>&nbsp;</p><p>Thank you for your continued trust in us.<br>&nbsp;</p><p>Best regards,</p>', 'Your deposit of {{amount}} {{site_currency}} has been successfully credited to your account. Thank you!', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the payment gateway\",\"method_currency\":\"Currency of the payment gateway\",\"method_amount\":\"Amount after conversion between base currency and gateway currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '2024-05-25 12:00:00', '2024-08-10 07:06:22'),
(30, 'DEPOSIT_REQUEST', 'Deposit - Manual - Requested', 'Deposit Request Received - Awaiting Approval', '<p>Thank you for your recent deposit request of {{amount}} {{site_currency}}&nbsp;to your account. We have successfully received your request and it is now pending approval from our admin team.<br>&nbsp;</p><p>Here are the details of your transaction:</p><p>&nbsp;</p><p><strong>Deposit Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Receivable Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Paid&nbsp;via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p>&nbsp;</p><p>Our team is reviewing your request to ensure everything is in order. You will receive a follow-up email once the deposit has been approved and credited to your account. This process typically takes 2 days.<br>&nbsp;</p><p>In the meantime, you can track the status of your request by logging into your account.<br>&nbsp;</p><p>If you have any questions or need further assistance, please do not hesitate to contact our support team. We are here to help!<br>&nbsp;</p><p>Thank you for your patience and for choosing us.</p><p>Best regards,</p>', 'Your deposit request of {{amount}} {{site_currency}} is pending admin approval. We\'ll notify you once it\'s approved. Thank you!', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the payment gateway\",\"method_currency\":\"Currency of the payment gateway\",\"method_amount\":\"Amount after conversion between base currency and gateway currency\"}', 1, 1, '2024-05-25 12:00:00', '2024-08-10 07:07:32'),
(31, 'DEPOSIT_APPROVE', 'Deposit - Manual - Approved', 'Deposit Request Approved - Funds Successfully Credited', '<p>We are delighted to inform you that your recent deposit request of {{amount}} {{site_currency}}&nbsp;has been approved by our admin team and the funds have been successfully credited to your account.<br>&nbsp;</p><p>Here are the details of your transaction:<br>&nbsp;</p><p><strong>Deposit Amount:</strong>&nbsp;{{amount}} {{site_currency}}</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Received Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Paid&nbsp;via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Post Balance:&nbsp;</strong>{{post_balance}}&nbsp;{{site_currency}}<br>&nbsp;</p><p>You can view the updated balance and transaction history by logging into your account.<br>&nbsp;</p><p>If you have any questions or need further assistance, please do not hesitate to contact our support team. We are here to help!<br>&nbsp;</p><p>Thank you for your patience and for choosing us.</p><p>Best regards,</p>', 'Good news! Your deposit request of {{amount}} {{site_currency}} has been approved and the funds are now credited to your account. Thank you!', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the payment gateway\",\"method_currency\":\"Currency of the payment gateway\",\"method_amount\":\"Amount after conversion between base currency and gateway currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '2024-05-25 12:00:00', '2024-08-10 07:05:15'),
(32, 'DEPOSIT_REJECT', 'Deposit - Manual - Rejected', 'Deposit Request Update - Request Rejected', '<p>We regret to inform you that your recent deposit request of {{amount}} {{site_currency}} has been reviewed and unfortunately, it has been rejected by our admin team.<br>&nbsp;</p><p>Here are the details of your transaction:<br>&nbsp;</p><p><strong>Requested Amount:</strong>&nbsp;{{amount}} {{site_currency}}<br>&nbsp;</p><p><strong>Charge:</strong>&nbsp;{{charge}} {{site_currency}}</p><p><strong>Conversion Rate:</strong>&nbsp;1 {{site_currency}} = {{rate}} {{method_currency}}</p><p><strong>Paid Amount:</strong>&nbsp;{{method_amount}} {{method_currency}}</p><p><strong>Paid&nbsp;via:</strong>&nbsp;{{method_name}}<br>&nbsp;</p><p><strong>Transaction Number:</strong>&nbsp;{{trx}}</p><p><strong>Rejection Message:</strong>&nbsp;</p><p>{{rejection_message}}</p><p>&nbsp;</p><p>We understand that this may be disappointing news. Please rest assured that no funds have been debited from your account for this request.<br>&nbsp;</p><p>If you believe this rejection was in error or if you have any questions, please do not hesitate to contact our support team. We are here to assist you in resolving any issues and to guide you through the process if you wish to submit a new deposit request.<br>&nbsp;</p><p>Thank you for your understanding and for choosing us.</p><p>Best regards,</p>', 'We\'re sorry to inform you that your deposit request of {{amount}} {{site_currency}} has been rejected. For details, please check your email or contact our support team. Thank you.', '{\"trx\":\"The transaction number for the plan subscription\",\"amount\":\"Price of the subscription plan\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the payment gateway\",\"method_currency\":\"Currency of the payment gateway\",\"method_amount\":\"Amount after conversion between base currency and gateway currency\",\"rejection_message\":\"Rejection message by the admin\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-10 07:07:07'),
(34, 'JOB_POST', 'Job Posted', 'Job Posting Confirmation', '<p>We are pleased to inform you that your job titled <strong>\"{{title}}”</strong> has been successfully posted on our platform.</p><p>&nbsp;</p><p>Job Details:</p><ul><li><strong>Job Code:</strong> {{job_code}}</li><li><strong>Job Cost:</strong> {{amount}} {{site_currency}}</li></ul><p>&nbsp;</p><p>Thank you for choosing our platform to post your job. We’ll keep you updated on any developments.</p><p>&nbsp;</p><p>If you have any questions or need assistance, feel free to contact our support team.</p><p>&nbsp;</p><p>Best regards,</p>', 'Your job \"{{title}}\" (Code: {{job_code}}) has been posted. Job Cost is {{amount}} {{site_currency}}. Thank you for using our platform!', '{\"job_code\":\"The unique identifier of the job\",\"title\":\"Title of the job\",\"amount\":\"The costing amount for the job\"}', 1, 1, '2021-11-03 12:00:00', '2024-08-29 05:17:24'),
(39, 'JOB_APPLICATION_SUBMIT', 'Job Application Submitted', 'Job Application Confirmation', '<p>Thank you for applying for the <strong>\"{{title}}\" </strong>job. Your application is currently <strong>pending review</strong> by the job author.</p><p>&nbsp;</p><p>Here’s a quick summary of your application:</p><ul><li><strong>Job Title:</strong> {{title}}</li><li><strong>Job Code:</strong> {{job_code}}</li></ul><p>&nbsp;</p><p>Once the job author has reviewed your application, you will receive an update on the next steps. In the meantime, if you have any questions, feel free to reach out.</p><p>&nbsp;</p><p>Thank you for your interest, and good luck with your application!</p><p>&nbsp;</p><p>Best regards,</p>', 'Thank you for applying for the \"{{title}}\" job. Your application is pending review by the job author. We\'ll notify you of the next steps soon.', '{\"job_code\":\"The unique identifier of the job\",\"title\":\"Title of the job\"}', 1, 1, '2021-11-03 12:00:00', '2024-09-12 06:03:21'),
(40, 'JOB_APPLICATION_APPROVE', 'Job Application Approved', 'Congratulations! Your Job Application Has Been Approved', '<p>We are pleased to inform you that your application for the position of <strong>{{title}}</strong> (Job Code: <strong>{{job_code}}</strong>) has been approved by the hiring team!</p><p>&nbsp;</p><p>This is a significant step in the application process, and we want to express our appreciation for your interest in this role. The next steps in the hiring process will be communicated to you shortly.</p><p>&nbsp;</p><p>If you have any questions or need further clarification, feel free to reach out to us.</p><p>&nbsp;</p><p>Thank you once again for your application, and we wish you the best of luck moving forward!</p><p>&nbsp;</p><p>Best regards,</p>', 'Congratulations! Your application for the {{title}} (Job Code: {{job_code}}) has been approved by the hiring team. We\'ll contact you with the next steps soon. Thank you!', '{\"job_code\":\"The unique identifier of the job\",\"title\":\"Title of the job\"}', 1, 1, '2021-11-03 12:00:00', '2024-09-17 10:19:10'),
(41, 'JOB_APPLICATION_REJECT', 'Job Application Rejected', 'Update on Your Job Application', '<p>Thank you for taking the time to apply for the <strong>{{title}}</strong> position at <strong>{{site_name}}</strong>. We appreciate your interest in joining our team and the effort you put into your application.</p><p>&nbsp;</p><p>After careful consideration, we regret to inform you that we have chosen to move forward with other candidates whose qualifications more closely match the needs of this role. Please know that this decision was not made lightly, and we encourage you to continue applying for future opportunities with us that align with your skills and experience.</p><p>&nbsp;</p><p>Once again, thank you for your interest in <strong>{{site_name}}</strong>. We wish you the best of luck in your job search and future career endeavors.</p><p>&nbsp;</p><p>Best regards,</p>', 'Thank you for applying for the {{title}} position at {{site_name}}. After careful review, we\'ve decided to move forward with other candidates. We appreciate your interest and encourage you to apply for future openings. Best of luck in your job search!', '{\"title\":\"Title of the job\"}', 1, 1, '2021-11-03 12:00:00', '2024-09-18 04:45:32'),
(42, 'ASSIGNED_JOB_COMPLETE', 'Assigned Job Completed', 'Job Completion and Payment Confirmation', '<p>We are happy to inform you that your work on the <strong>{{title}} </strong>(Job Code: {{job_code}}) has been successfully completed, and the job author has approved the outcome.</p><p>&nbsp;</p><p>Additionally, your compensation of {{amount}} {{site_currency}} has been processed and deposited into your account.</p><p>&nbsp;</p><p>Thank you for your excellent work on this project. If you have any questions or need further assistance, feel free to reach out.</p><p>&nbsp;</p><p>Best regards,</p>', 'Your work on {{title}} (Job Code: {{job_code}}) has been successfully completed and approved. A payment of {{amount}} {{site_currency}} has been processed and transferred to your account. Thank you for your hard work!', '{\"job_code\":\"The unique identifier of the job\",\"title\":\"Title of the job\",\"amount\":\"Compensation for the freelancer\"}', 1, 1, '2021-11-03 12:00:00', '2024-09-30 09:52:25'),
(43, 'ASSIGNED_JOB_DISPUTE', 'Assigned Job Disputed', 'Notification of Dispute for Assigned Job', '<p>We hope this message finds you well.</p><p>&nbsp;</p><p>We are writing to inform you that a dispute has been raised regarding the job titled <strong>\"{{title}}\" </strong>(Job Code: {{job_code}}). The dispute is currently under review by our administrative team.</p><p>&nbsp;</p><p>We understand that disputes can be concerning, and we want to assure you that our team is dedicated to resolving this matter as quickly and fairly as possible. Here’s the reason of this dispute.</p><p>&nbsp;</p><p>\"{{reason}}\"</p><p>&nbsp;</p><p>Thank you for your patience and cooperation during this process. If you have any immediate questions or concerns, please do not hesitate to reach out to us.</p><p>&nbsp;</p><p>Best regards,</p>', 'We wanted to let you know that a dispute has been raised regarding job #{{job_code}} \"{{title}}\". Our admin team is reviewing the matter and will update you soon. Thank you for your patience!', '{\"job_code\":\"The unique identifier of the job\",\"title\":\"Title of the job\",\"reason\":\"The reason for disputing the assigned job\"}', 1, 1, '2021-11-03 12:00:00', '2024-10-05 10:50:11'),
(44, 'DISPUTED_JOB_TAKE_ACTION', 'Disputed Job Action Taken', 'Resolution Update for Disputed Job', '<p>We hope this email finds you well. We are writing to inform you that the dispute regarding the job titled <strong>\"{{title}}\" </strong>(Job Code: {{job_code}}) has been resolved. Our admin team has carefully reviewed the case and taken the necessary actions to ensure a fair outcome for both parties.</p><p>&nbsp;</p><p>Here is a summary of the resolution:</p><ul><li><strong>Total Compensation:</strong> {{amount}} {{site_currency}}</li><li><strong>Amount Credited to Author Balance:</strong> {{author_amount}} {{site_currency}}</li><li><strong>Amount Credited to Freelancer Balance:</strong> {{freelancer_amount}} {{site_currency}}</li><li><strong>Transaction Reference Number:</strong> {{trx}}</li></ul><p>&nbsp;</p><p>We appreciate your patience throughout this process. If you have any further questions or concerns, please do not hesitate to reach out to us.</p><p>&nbsp;</p><p>Thank you for using our platform, and we look forward to your continued success in your future endeavors.</p><p>&nbsp;</p><p>Best regards,</p>', 'The dispute for job \"{{title}}\" (Job Code: {{job_code}}) has been resolved. Author will receive {{author_amount}} {{site_currency}}, and Freelancer will receive {{freelancer_amount}} {{site_currency}}. Transaction Ref: {{trx}}. Thank you for your patience.', '{\"job_code\":\"The unique identifier of the job\",\"title\":\"Title of the job\",\"amount\":\"The compensation amount for the freelancer to complete the job\",\"author_amount\":\"The amount that will be added to the author balance\",\"freelancer_amount\":\"The amount that will be added to the freelancer balance\",\"trx\":\"Transaction number for taking action\"}', 1, 1, '2021-11-03 12:00:00', '2024-10-09 07:13:58'),
(45, 'FREELANCER_APPLICATION_ACCEPT', 'Freelancer Application Accepted', 'Congratulations! Your Freelancer Application Has Been Approved', '<p>We are thrilled to inform you that your application to join our platform as a freelancer has been successfully approved! 🎉</p><p>&nbsp;</p><p>We appreciate your interest in becoming a part of our community, and we are excited to see the incredible work you\'ll do on our platform. You now have full access to freelancer functionalities, allowing you to apply for jobs, showcase your skills, and collaborate with clients from around the world.</p><p>&nbsp;</p><p>Should you have any questions or need assistance, feel free to contact our support team. We\'re here to help you succeed.</p><p>&nbsp;</p><p>Welcome to the team, and we wish you the best of luck on your freelance journey!</p><p>&nbsp;</p><p>Best regards,</p>', 'Congratulations! Your application to become a freelancer has been approved. 🎉 You can now apply for jobs and start working on our platform. Log in to get started. Best of luck! – {{site_name}}', '[]', 1, 1, '2024-10-16 12:00:00', '2024-10-16 05:56:41'),
(46, 'FREELANCER_APPLICATION_REJECT', 'Freelancer Application Rejected', 'Application Status Update', '<p>Thank you for your interest in joining our platform.</p><p>&nbsp;</p><p>We regret to inform you that your application to become a freelancer has been rejected. After careful consideration, we have determined that we cannot move forward with your application at this time. Here, we have provided why you have rejected.</p><p>&nbsp;</p><p>\"{{reason}}\"</p><p>&nbsp;</p><p>Please note that, as per our policy, once an application is rejected, you will not be able to reapply in the future. We appreciate your understanding in this matter.</p><p>&nbsp;</p><p>Thank you for your interest in working with us, and we wish you all the best in your future endeavors.</p><p>&nbsp;</p><p>Best regards,</p>', 'Your application to become a freelancer has been rejected. Please note that you are not eligible to reapply in the future. Thank you for your interest. – {{site_name}}', '{\"reason\":\"The reason for the freelancer\'s rejection\"}', 1, 1, '2024-10-16 12:00:00', '2024-10-16 07:00:00'),
(47, 'FREELANCER_BAN', 'Freelancer Banned', 'Important Notice: Freelancer Profile Has Banned', '<p>We regret to inform you that your freelancer account on {{site_name}} has been banned due to a violation of our platform\'s terms and conditions.</p><p>&nbsp;</p><p><strong>Reason for Ban:</strong><br>{{reason}}</p><p>&nbsp;</p><p>This decision is final, and your access to the freelancer features of the platform is now restricted. You will no longer be able to apply for new jobs or access your ongoing projects.</p><p>&nbsp;</p><p>If you believe this action was taken in error or you have any further questions, please feel free to contact our support team.</p><p>&nbsp;</p><p>We appreciate your understanding and cooperation.</p><p>&nbsp;</p><p>Best regards,</p>', 'Your freelancer account on {{site_name}} has been banned due to a violation of our terms and conditions. For further details or to contact support, please email us.', '{\"reason\":\"The reason for banning the freelancer\"}', 1, 1, '2024-10-16 12:00:00', '2024-10-16 11:33:13'),
(48, 'FREELANCER_UNBAN', 'Freelancer Unbanned', 'Your Freelancer Account Has Been Unbanned', '<p>We are pleased to inform you that your freelancer account on {{site_name}} has been successfully unbanned. After reviewing your case, we have decided to restore your access to the platform. You may now log in and continue offering your services as a freelancer.</p><p>&nbsp;</p><p>We appreciate your cooperation and encourage you to adhere to our terms and guidelines to avoid any future interruptions.</p><p>&nbsp;</p><p>If you have any questions or require assistance, please feel free to reach out to us.</p><p>&nbsp;</p><p>Thank you for being a valued member of our platform.</p><p>&nbsp;</p><p>Best regards,</p>', 'Your freelancer account on {{site_name}} has been unbanned. You can now log in and resume your services. For any questions, contact us. Thank you!', '[]', 1, 1, '2024-10-16 12:00:00', '2024-10-16 11:53:08');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE `plugins` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `script` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'object',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`id`, `act`, `name`, `image`, `script`, `shortcode`, `status`, `created_at`, `updated_at`) VALUES
(1, 'google-analytics', 'Google Analytics', 'analytics.png', '<script async src=\"https://www.googletagmanager.com/gtag/js?id={{app_key}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{app_key}}\");\n                </script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"----------------\"}}', 0, NULL, '2025-01-14 06:12:22'),
(2, 'google-recaptcha2', 'Google Recaptcha 2', 'captcha.png', '\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>', '{\"site_key\":{\"title\":\"Site Key\",\"value\":\"----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"----------------\"}}', 0, NULL, '2025-01-14 06:12:28'),
(3, 'facebook-messenger', 'Facebook Messenger', 'messenger.png', '<div id=\"fb-root\"></div>\n<div id=\"fb-customer-chat\" class=\"fb-customerchat\"></div>\n\n<script>\n    var chatbox = document.getElementById(\'fb-customer-chat\');\n    chatbox.setAttribute(\"page_id\", {{page_id}});\n    chatbox.setAttribute(\"attribution\", \"biz_inbox\");\n</script>\n\n<!-- Your SDK code -->\n<script>\n    window.fbAsyncInit = function() {\n    FB.init({\n        xfbml            : true,\n        version          : \'v17.0\'\n    });\n    };\n\n    (function(d, s, id) {\n    var js, fjs = d.getElementsByTagName(s)[0];\n    if (d.getElementById(id)) return;\n    js = d.createElement(s); js.id = id;\n    js.src = \'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js\';\n    fjs.parentNode.insertBefore(js, fjs);\n    }(document, \'script\', \'facebook-jssdk\'));\n</script>', '{\"page_id\":{\"title\":\"Page Id\",\"value\":\"-----------------\"}}', 0, NULL, '2025-01-14 06:12:17'),
(4, 'tawk-chat', 'Tawk.to', 'tawk.png', '<script>\n                        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();\n                        (function(){\n                        var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];\n                        s1.async=true;\n                        s1.src=\"https://embed.tawk.to/{{app_key}}\";\n                        s1.charset=\"UTF-8\";\n                        s1.setAttribute(\"crossorigin\",\"*\");\n                        s0.parentNode.insertBefore(s1,s0);\n                        })();\n                    </script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"----------------\"}}', 0, NULL, '2025-01-14 06:12:33');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `site_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_cur` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'site currency text',
  `cur_sym` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'site currency symbol',
  `per_page_item` int UNSIGNED NOT NULL DEFAULT '20',
  `fraction_digit` tinyint UNSIGNED NOT NULL DEFAULT '2',
  `date_format` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MDY',
  `email_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_body` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `universal_shortcodes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `primary_color` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signup` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'user registration',
  `enforce_ssl` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'enforce ssl',
  `agree_policy` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'accept terms and policy',
  `strong_pass` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'enforce strong password',
  `kc` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'kyc confirmation',
  `ec` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'email confirmation',
  `ea` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'email alert',
  `sc` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'sms confirmation',
  `sa` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'sms alert',
  `site_maintenance` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `language` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `active_theme` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'primary',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_cur`, `cur_sym`, `per_page_item`, `fraction_digit`, `date_format`, `email_from`, `email_template`, `sms_body`, `sms_from`, `mail_config`, `sms_config`, `universal_shortcodes`, `primary_color`, `secondary_color`, `signup`, `enforce_ssl`, `agree_policy`, `strong_pass`, `kc`, `ec`, `ea`, `sc`, `sa`, `site_maintenance`, `language`, `active_theme`, `created_at`, `updated_at`) VALUES
(1, 'TonaJob', 'USD', '$', 20, 2, 'd-m-Y', 'info@tonatheme.com', '<title></title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\r\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n<style type=\"text/css\">\r\n    @media screen {\r\n		@font-face {\r\n		  font-family: \'Lato\';\r\n		  font-style: normal;\r\n		  font-weight: 400;\r\n		  src: local(\'Lato Regular\'), local(\'Lato-Regular\'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format(\'woff\');\r\n		}\r\n		\r\n		@font-face {\r\n		  font-family: \'Lato\';\r\n		  font-style: normal;\r\n		  font-weight: 700;\r\n		  src: local(\'Lato Bold\'), local(\'Lato-Bold\'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format(\'woff\');\r\n		}\r\n		\r\n		@font-face {\r\n		  font-family: \'Lato\';\r\n		  font-style: italic;\r\n		  font-weight: 400;\r\n		  src: local(\'Lato Italic\'), local(\'Lato-Italic\'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format(\'woff\');\r\n		}\r\n		\r\n		@font-face {\r\n		  font-family: \'Lato\';\r\n		  font-style: italic;\r\n		  font-weight: 700;\r\n		  src: local(\'Lato Bold Italic\'), local(\'Lato-BoldItalic\'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format(\'woff\');\r\n		}\r\n    }\r\n    \r\n\r\n    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }\r\n    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }\r\n    img { -ms-interpolation-mode: bicubic; }\r\n\r\n    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }\r\n    table { border-collapse: collapse !important; }\r\n    body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }\r\n\r\n    a[x-apple-data-detectors] {\r\n        color: inherit !important;\r\n        text-decoration: none !important;\r\n        font-size: inherit !important;\r\n        font-family: inherit !important;\r\n        font-weight: inherit !important;\r\n        line-height: inherit !important;\r\n    }\r\n\r\n    div[style*=\"margin: 16px 0;\"] { margin: 0 !important; }\r\n</style>\r\n\r\n\r\n\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\r\n\r\n    <tbody><tr>\r\n        <td bgcolor=\"black\" align=\"center\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\r\n                <tbody><tr>\r\n                    <td align=\"center\" valign=\"top\" style=\"padding: 40px 10px 40px 10px;\">\r\n                        <a href=\"#0\" target=\"_blank\">\r\n                            <img alt=\"Logo\" src=\"https://script.tonatheme.com/tonajob/assets/universal/images/logoFavicon/logo_light.png\" width=\"180\" height=\"180\" style=\"display: block;  font-family: \'Lato\', Helvetica, Arial, sans-serif; color: #ffffff; font-size: 18px;\" border=\"0\">\r\n                        </a>\r\n                    </td>\r\n                </tr>\r\n            </tbody></table>\r\n        </td>\r\n    </tr>\r\n\r\n    <tr>\r\n        <td bgcolor=\"black\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\r\n                <tbody><tr>\r\n                    <td bgcolor=\"#ffffff\" align=\"center\" valign=\"top\" style=\"padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;\">\r\n                      <h1 style=\"font-size: 22px; font-weight: 400; margin: 0; border-bottom: 1px solid #727272; width: max-content;\">Hello {{fullname}} ({{username}})</h1>\r\n                    </td>\r\n                </tr>\r\n            </tbody></table>\r\n        </td>\r\n    </tr>\r\n\r\n    <tr>\r\n        <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 0px 10px 0px 10px;\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\r\n\r\n              <tbody><tr>\r\n                <td bgcolor=\"#ffffff\" align=\"left\" style=\"padding: 20px 30px 40px 30px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px; text-align: center;\">\r\n                  <p style=\"margin: 0;\">{{message}}</p>\r\n                </td>\r\n              </tr>\r\n            </tbody></table>\r\n        </td>\r\n    </tr>\r\n\r\n    <tr>\r\n        <td bgcolor=\"#f4f4f4\" align=\"center\" style=\"padding: 30px 10px 0px 10px;\">\r\n            <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"700\">\r\n                <tbody><tr>\r\n                  <td bgcolor=\"black\" align=\"center\" style=\"padding: 30px 30px 30px 30px; border-radius: 4px 4px 4px 4px; color: #666666; font-family: \'Lato\', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;\">\r\n                    <h2 style=\"font-size: 20px; font-weight: 400; color: white; margin: 0;\">©{{site_name}} All Rights Reserved.</h2>\r\n                  </td>\r\n                </tr>\r\n            </tbody></table>\r\n        </td>\r\n    </tr>\r\n</tbody></table>', 'Hi {{fullname}} ({{username}}), {{message}}', 'TonaJob', '{\"name\":\"php\"}', '{\"name\":\"custom\",\"nexmo\":{\"api_key\":\"-----\",\"api_secret\":\"-----\"},\"twilio\":{\"account_sid\":\"-----\",\"auth_token\":\"-----\",\"from\":\"-----\"},\"custom\":{\"method\":\"get\",\"url\":\"https:\\/\\/hostname\\/demo-api-v1\",\"headers\":{\"name\":[\"api_key\",\"Demo API\"],\"value\":[\"test_api_key\",\"Demo API\"]},\"body\":{\"name\":[\"from_number\",\"Demo body API\"],\"value\":[\"123456\",\"Demo body API\"]}}}', '{\r\n    \"site_name\":\"Name of your site\",\r\n    \"site_currency\":\"Currency of your site\",\r\n    \"currency_symbol\":\"Symbol of currency\"\r\n}', 'dfc69b', '563029', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 'primary', NULL, '2025-01-14 06:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `site_data`
--

CREATE TABLE `site_data` (
  `id` bigint UNSIGNED NOT NULL,
  `data_key` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_data`
--

INSERT INTO `site_data` (`id`, `data_key`, `data_info`, `created_at`, `updated_at`) VALUES
(1, 'seo.data', '{\"seo_image\":\"1\",\"keywords\":[\"Job Posting\",\"Hire Freelancers\",\"Remote Jobs\",\"Freelancer Reviews\",\"Freelancer Workspace\",\"Job Earnings\",\"bid\",\"freelancer\",\"freelancing\",\"job\",\"jobs\",\"marketplace\",\"micro\",\"micro job\",\"micro workers\",\"php\",\"script\",\"sell\",\"service\",\"work at home\",\"work online\"],\"description\":\"TonaJob is a platform where users can post jobs and hire freelancers for various tasks. Manage job listings, track progress, and handle disputes easily. Freelancers can apply for jobs, complete tasks, and receive reviews. Discover a streamlined system for managing freelance work and projects with ease.\",\"social_title\":\"TonaJob\",\"social_description\":\"TonaJob is a powerful platform connecting job posters with skilled freelancers. Post jobs, hire top talent, manage tasks, and track job progress seamlessly. Whether you\'re looking to complete projects or find work, TonaJob simplifies the process for both buyers and freelancers.\",\"image\":\"6785258b40d041736779147.png\"}', '2023-08-15 08:11:35', '2025-01-13 14:43:43'),
(8, 'cookie.data', '{\"short_details\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience\",\"details\":\"<p>Types of information we gather&nbsp;<br>&nbsp;<\\/p><p><strong>Personal Information:<\\/strong>&nbsp;When users register on PnixFund, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Campaign Information:<\\/strong>&nbsp;We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong>&nbsp;For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong>&nbsp;We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p><p>&nbsp;<\\/p><p>Ensuring the security of your information<\\/p><p><strong>User Accounts:<\\/strong>&nbsp;We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>&nbsp;Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>&nbsp;Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Is any information shared with external parties?<\\/p><p><br>&nbsp;<\\/p><p><strong>No, we do not sell,<\\/strong>&nbsp;trade, or otherwise transfer users\' personally identifiable information to outside parties without explicit consent.<\\/p><p><strong>Exceptional Circumstances:<\\/strong>&nbsp;We may disclose user information in response to legal requirements, enforcement of policies, or protection of rights, property, or safety.<\\/p><p>Duration of information retention<\\/p><p><strong>User Accounts:<\\/strong>&nbsp;We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>&nbsp;Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>&nbsp;Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Our policies regarding data usage&nbsp;<br>&nbsp;<\\/p><p><strong>Personal Information:<\\/strong>&nbsp;When users register on PnixFund, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Campaign Information:<\\/strong>&nbsp;We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong>&nbsp;For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong>&nbsp;We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p><p>&nbsp;<\\/p>\",\"status\":1}', NULL, '2025-01-13 12:03:25'),
(9, 'maintenance.data', '{\"heading\":\"Website Under Maintenance\",\"details\":\"<p>We\'re currently sprucing things up to provide you with an even better browsing experience. Our website is temporarily undergoing maintenance, but we\'ll be back online shortly.<\\/p><p>&nbsp;<\\/p><p>In the meantime, if you have any urgent inquiries or need assistance, feel free to reach out to us at <strong>example@example.com<\\/strong>. We apologize for any inconvenience caused and appreciate your patience.<br>&nbsp;<\\/p><p>&nbsp;<\\/p><p>Thank you for your understanding!<\\/p>\"}', NULL, '2025-01-13 12:05:18'),
(11, 'policy_pages.element', '{\"title\":\"Privacy Policy\",\"details\":\"<p>Types of information we gather\\u00a0<br \\/>\\u00a0<\\/p><p><strong>Personal Information:<\\/strong>\\u00a0When users register on PnixFund, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Campaign Information:<\\/strong>\\u00a0We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong>\\u00a0For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong>\\u00a0We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p><p>\\u00a0<\\/p><p>Ensuring the security of your information<\\/p><p><strong>User Accounts:<\\/strong>\\u00a0We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>\\u00a0Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>\\u00a0Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Is any information shared with external parties?<\\/p><p><br \\/>\\u00a0<\\/p><p><strong>No, we do not sell,<\\/strong>\\u00a0trade, or otherwise transfer users\' personally identifiable information to outside parties without explicit consent.<\\/p><p><strong>Exceptional Circumstances:<\\/strong>\\u00a0We may disclose user information in response to legal requirements, enforcement of policies, or protection of rights, property, or safety.<\\/p><p>Duration of information retention<\\/p><p><strong>User Accounts:<\\/strong>\\u00a0We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>\\u00a0Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>\\u00a0Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Our policies regarding data usage\\u00a0<br \\/>\\u00a0<\\/p><p><strong>Personal Information:<\\/strong>\\u00a0When users register on PnixFund, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Campaign Information:<\\/strong>\\u00a0We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong>\\u00a0For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong>\\u00a0We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p>\"}', '2023-11-09 04:17:26', '2025-01-13 12:04:19'),
(12, 'policy_pages.element', '{\"title\":\"Terms of Service\",\"details\":\"<p>Types of information we gather\\u00a0<br \\/>\\u00a0<\\/p><p><strong>Personal Information:<\\/strong>\\u00a0When users register on PnixFund, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Campaign Information:<\\/strong>\\u00a0We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong>\\u00a0For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong>\\u00a0We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p><p>\\u00a0<\\/p><p>Ensuring the security of your information<\\/p><p><strong>User Accounts:<\\/strong>\\u00a0We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>\\u00a0Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>\\u00a0Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Is any information shared with external parties?<\\/p><p><br \\/>\\u00a0<\\/p><p><strong>No, we do not sell,<\\/strong>\\u00a0trade, or otherwise transfer users\' personally identifiable information to outside parties without explicit consent.<\\/p><p><strong>Exceptional Circumstances:<\\/strong>\\u00a0We may disclose user information in response to legal requirements, enforcement of policies, or protection of rights, property, or safety.<\\/p><p>Duration of information retention<\\/p><p><strong>User Accounts:<\\/strong>\\u00a0We retain user account information for as long as the account remains active or until the user requests its deletion.<\\/p><p><strong>Campaign Data:<\\/strong>\\u00a0Campaign information is retained for a reasonable period after the campaign\'s conclusion to facilitate auditing, reporting, and dispute resolution.<\\/p><p><strong>Donation Records:<\\/strong>\\u00a0Donation records are retained for compliance purposes and may be stored for a period required by applicable laws or regulations.<\\/p><p>Our policies regarding data usage\\u00a0<br \\/>\\u00a0<\\/p><p><strong>Personal Information:<\\/strong>\\u00a0When users register on PnixFund, we collect basic personal details such as name, email address, and optionally, profile pictures.<\\/p><p><strong>Campaign Information:<\\/strong>\\u00a0We collect information provided by campaign creators, including campaign descriptions, goals, and supporting media content.<\\/p><p><strong>Donation Information:<\\/strong>\\u00a0For donation processing, we collect payment details such as credit card information or payment gateway credentials.<\\/p><p><strong>Usage Data:<\\/strong>\\u00a0We may collect non-personal information related to user interactions with the platform, such as IP addresses, browser type, and device identifiers.<\\/p>\"}', '2023-11-09 04:17:51', '2025-01-13 12:03:59'),
(51, 'footer.content', '{\"footer_text\":\"MicroJob \\u2013 Your trusted platform for freelance services. Connect with talented professionals and get your projects done efficiently, affordably, and securely.\",\"copyright_text\":\"\\u00a9 Copyright 2025. All rights reserved.\",\"background_image\":\"66bdc01f495721723711519.png\"}', '2024-01-28 04:51:36', '2025-01-13 09:17:43'),
(52, 'footer.element', '{\"social_icon\":\"<i class=\\\"ti ti-brand-facebook\\\"><\\/i>\",\"url\":\"https:\\/\\/www.facebook.com\\/\"}', '2024-01-28 04:52:44', '2024-06-03 03:39:20'),
(53, 'footer.element', '{\"social_icon\":\"<i class=\\\"ti ti-brand-x\\\"><\\/i>\",\"url\":\"https:\\/\\/twitter.com\\/\"}', '2024-01-28 04:56:10', '2024-06-03 03:39:42'),
(54, 'footer.element', '{\"social_icon\":\"<i class=\\\"ti ti-brand-linkedin\\\"><\\/i>\",\"url\":\"https:\\/\\/www.linkedin.com\\/\"}', '2024-01-28 05:01:24', '2024-06-03 03:40:22'),
(55, 'footer.element', '{\"social_icon\":\"<i class=\\\"ti ti-brand-instagram\\\"><\\/i>\",\"url\":\"https:\\/\\/www.instagram.com\\/\"}', '2024-01-28 05:02:20', '2024-06-03 03:40:40'),
(56, 'login.content', '{\"form_heading\":\"Log In\",\"submit_button_text\":\"Log In\",\"background_image\":\"67837446adc071736668230.png\"}', '2024-01-29 00:36:20', '2025-01-12 07:50:31'),
(57, 'register.content', '{\"form_heading\":\"Sign Up\",\"submit_button_text\":\"Sign Up\",\"background_image\":\"6783746e445f11736668270.png\"}', '2024-01-29 01:10:09', '2025-01-12 07:51:10'),
(58, 'kyc.content', '{\"verification_required_heading\":\"Verification Needed\",\"verification_required_details\":\"Ensure your account security and access exclusive features by providing the necessary verification details.\",\"verification_pending_heading\":\"Verification Pending\",\"verification_pending_details\":\"Your request for verification is in progress. We appreciate your patience as we ensure the security of your account.\"}', '2024-01-29 04:35:38', '2024-03-17 03:13:03'),
(59, 'forgot_password.content', '{\"form_heading\":\"Recover your account\",\"submit_button_text\":\"Next\",\"background_image\":\"6783743b1f2db1736668219.png\"}', '2024-01-29 23:31:38', '2025-01-12 07:50:19'),
(60, 'code_verification.content', '{\"form_heading\":\"Code Verification\",\"submit_button_text\":\"Submit\",\"background_image\":\"6783738aeb4891736668042.png\"}', '2024-01-30 00:17:10', '2025-01-12 07:47:23'),
(61, 'password_reset.content', '{\"form_heading\":\"Reset Password\",\"submit_button_text\":\"Submit\",\"background_image\":\"678374620a57b1736668258.png\"}', '2024-01-30 01:10:22', '2025-01-12 07:50:58'),
(62, 'email_confirm.content', '{\"form_heading\":\"Verify Email Address\",\"submit_button_text\":\"Submit\",\"background_image\":\"678374263150a1736668198.png\"}', '2024-01-30 03:44:37', '2025-01-12 07:49:58'),
(63, 'mobile_confirm.content', '{\"form_heading\":\"Verify Phone Number\",\"submit_button_text\":\"Submit\",\"background_image\":\"67837455b70981736668245.png\"}', '2024-01-30 04:09:27', '2025-01-12 07:50:46'),
(64, 'user_ban.content', '{\"form_heading\":\"You Are Banned\",\"background_image\":\"678374afb30e61736668335.png\"}', '2024-01-30 04:37:56', '2025-01-12 07:52:16'),
(65, '2fa_confirm.content', '{\"form_heading\":\"Two Factor Authentication\",\"form_text\":\"To confirm your identity, please enter the verification code generated by Google Authenticator below.\",\"submit_button_text\":\"Submit\",\"background_image\":\"6783737466a5e1736668020.png\"}', '2024-01-30 06:18:23', '2025-01-12 07:47:01'),
(80, 'banner.content', '{\"subtitle\":\"Micro Job Mastery\",\"title_first_part\":\"YOUR JOURNEY TO\",\"title_second_part\":\"FREELANCE\",\"title_third_part\":\"TRIUMPH\",\"video_button_name\":\"WATCH VIDEO\",\"youtube_video_id\":\"uWQ_8CtvzoY\",\"background_image\":\"66bc9bb05b72e1723636656.png\",\"image\":\"66bc9bb0ab4951723636656.png\"}', '2024-03-18 03:23:22', '2025-01-13 12:11:29'),
(100, 'faq.content', '{\"section_heading_subtitle\":\"Frequently Asked Questions\",\"section_heading_title\":\"Unveiling Insights and Providing Clarity\"}', '2024-03-19 04:42:04', '2024-08-17 10:51:42'),
(107, 'blog.content', '{\"section_heading_subtitle\":\"Latest Articles\",\"section_heading_title\":\"Browse Through Our Latest Blog Articles\",\"section_button_name\":\"View All\"}', '2024-03-20 00:06:54', '2025-01-13 09:32:31'),
(127, 'breadcrumb.content', '{\"background_image\":\"66bdc817cee721723713559.png\"}', '2024-03-20 23:14:55', '2024-08-15 09:19:19'),
(128, 'contact_us.content', '{\"latitude\":\"39.925533\",\"longitude\":\"32.866287\",\"image\":\"678373b7f20131736668087.png\"}', '2024-03-22 23:09:17', '2025-01-12 07:48:08'),
(129, 'contact_us.element', '{\"icon\":\"<i class=\\\"ti ti-map-pin\\\"><\\/i>\",\"heading\":\"Address\",\"data\":\"USA, Florida, 100 Old City House\"}', '2024-03-22 23:10:21', '2025-01-13 12:02:45'),
(130, 'contact_us.element', '{\"icon\":\"<i class=\\\"ti ti-mail\\\"><\\/i>\",\"heading\":\"Email Address\",\"data\":\"example@example.com\"}', '2024-03-22 23:11:05', '2024-10-21 11:40:57'),
(131, 'contact_us.element', '{\"icon\":\"<i class=\\\"ti ti-phone\\\"><\\/i>\",\"heading\":\"Phone\",\"data\":\"0123-456-789\"}', '2024-03-22 23:16:21', '2024-10-21 11:40:15'),
(173, 'partner.element', '{\"image\":\"66bc9f1016a051723637520.png\"}', '2024-06-03 02:36:36', '2024-08-14 12:12:00'),
(174, 'partner.element', '{\"image\":\"66bc9f030374a1723637507.png\"}', '2024-06-03 02:40:30', '2024-08-14 12:11:47'),
(175, 'partner.element', '{\"image\":\"66bc9efc2054c1723637500.png\"}', '2024-06-03 02:41:08', '2024-08-14 12:11:40'),
(176, 'partner.element', '{\"image\":\"66bc9ef5b81cd1723637493.png\"}', '2024-06-03 02:42:27', '2024-08-14 12:11:33'),
(177, 'partner.element', '{\"image\":\"66bc9eee9f3ec1723637486.png\"}', '2024-06-03 02:43:05', '2024-08-14 12:11:26'),
(178, 'partner.element', '{\"image\":\"66bc9ee35db1e1723637475.png\"}', '2024-06-03 02:43:22', '2024-08-14 12:11:15'),
(182, 'categories.content', '{\"section_heading_subtitle\":\"Categories\",\"section_heading_title\":\"Navigate Through Our Vast Array of Categories\",\"section_button_name\":\"View All\"}', '2024-08-14 12:21:33', '2024-08-15 05:22:54'),
(183, 'call_to_action.content', '{\"first_heading_subtitle\":\"Express Yourself\",\"first_heading_title\":\"Step Into the Spotlight with Your Skills\",\"first_short_description\":\"Explore an array of freelance opportunities tailored to your expertise and preferences. From creative gigs to technical projects, find the perfect match for your skills and take the next step in your freelancing journey.\",\"first_button_name\":\"Find Your Job\",\"second_heading_subtitle\":\"Find Freelance Talent\",\"second_heading_title\":\"Transform Your Project Ideas into Reality\",\"second_short_description\":\"Need a project completed? Post your job and gain access to a diverse pool of talented freelancers. Describe your project, set your budget, and watch as skilled professionals bid to bring your vision to life.\",\"second_button_name\":\"Post Your Job\",\"first_background_image\":\"6784ff0636f5f1736769286.png\",\"second_background_image\":\"66bd8d775deea1723698551.png\"}', '2024-08-15 05:09:11', '2025-01-13 11:54:46'),
(184, 'latest_jobs.content', '{\"section_heading_subtitle\":\"Latest Jobs Here\",\"section_heading_title\":\"Stay Updated with Fresh Opportunities\",\"section_button_name\":\"View All\"}', '2024-08-15 05:24:42', '2024-08-15 05:24:42'),
(185, 'why_choose_us.content', '{\"section_heading_subtitle\":\"Why Choose Us\",\"section_heading_title\":\"What Makes Us Your Most Compelling Choice\",\"image\":\"66bd9493b28351723700371.png\"}', '2024-08-15 05:39:31', '2024-08-15 05:39:32'),
(186, 'why_choose_us.element', '{\"title\":\"Achieve top-notch results promptly\",\"short_description\":\"Get top-notch results fast from skilled freelancers, meeting your deadlines without compromise.\",\"image\":\"66bd94f5cb8861723700469.png\"}', '2024-08-15 05:41:09', '2024-08-15 05:41:09'),
(187, 'why_choose_us.element', '{\"title\":\"Payment satisfaction guaranteed\",\"short_description\":\"Pay only upon complete satisfaction, ensuring quality and peace of mind.\",\"image\":\"66bd953ee48ea1723700542.png\"}', '2024-08-15 05:42:22', '2024-08-15 05:42:22'),
(188, 'why_choose_us.element', '{\"title\":\"Rely on 24\\/7 assistance\",\"short_description\":\"Access round-the-clock assistance from our expert team for a smooth experience anytime, anywhere.\",\"image\":\"66bd9593e4a351723700627.png\"}', '2024-08-15 05:43:47', '2024-08-15 05:43:47'),
(189, 'performers.content', '{\"section_heading_subtitle\":\"Our Freelancers\",\"section_heading_title\":\"Freelancers with the Most Completed Jobs\",\"section_button_name\":\"View All\"}', '2024-08-15 05:53:05', '2025-01-13 10:46:53'),
(190, 'testimonials.content', '{\"section_heading_subtitle\":\"Testimonials\",\"section_heading_title\":\"Sharing Insights and Experiences\"}', '2024-08-15 06:03:45', '2024-08-15 06:03:45'),
(191, 'testimonials.element', '{\"client_name\":\"John Alverage\",\"client_designation\":\"CEO & Founder\",\"client_review\":\"I\'ve been using this platform for both finding freelance work and hiring freelancers, and I couldn\'t be happier with the results. The process is straightforward, and the talent pool is impressive. Highly recommend giving it a try!\",\"client_image\":\"66bd9acb4d2561723701963.jpg\"}', '2024-08-15 06:06:03', '2025-01-14 07:02:01'),
(192, 'testimonials.element', '{\"client_name\":\"Mark Smith\",\"client_designation\":\"Web Developer\",\"client_review\":\"Finding the perfect freelancer for my project was a breeze with this platform. The interface is user-friendly, and the variety of talented professionals available made it easy to find the right fit. I\'ll definitely be using it again!\",\"client_image\":\"66bd9b2144a821723702049.jpg\"}', '2024-08-15 06:07:29', '2024-08-15 06:07:29'),
(193, 'testimonials.element', '{\"client_name\":\"Shelton Shannon\",\"client_designation\":\"Web Designer\",\"client_review\":\"As a freelancer, this platform has been a game-changer for my career. The steady stream of high-quality projects and the supportive community have helped me thrive. I highly recommend it to both freelancers and clients alike!\",\"client_image\":\"66bd9bb1e985d1723702193.jpg\"}', '2024-08-15 06:09:53', '2024-08-15 06:09:53'),
(194, 'counters.element', '{\"title\":\"Job Posts\",\"counter_number\":\"1200\",\"short_description\":\"Explore diverse opportunities with active listings, Find your perfect match.\",\"image\":\"66bda4b99c66e1723704505.png\"}', '2024-08-15 06:48:25', '2024-10-05 07:20:48'),
(195, 'counters.element', '{\"title\":\"Project Completed\",\"counter_number\":\"5000\",\"short_description\":\"Join a community of achievers with a track record of success.\",\"image\":\"66bda4eaea9911723704554.png\"}', '2024-08-15 06:49:14', '2024-10-05 07:20:24'),
(196, 'counters.element', '{\"title\":\"Freelancers\",\"counter_number\":\"3500\",\"short_description\":\"Access skilled talent across various industries for your projects.\",\"image\":\"66bda529a36f31723704617.png\"}', '2024-08-15 06:50:17', '2024-10-05 07:20:56'),
(197, 'blog.element', '{\"title\":\"Top Features to Look for in a Micro Job Freelancing Platform\",\"description\":\"<p>In today\'s fast-paced world, freelancing has emerged as a popular choice for many professionals seeking flexibility, autonomy, and unlimited earning potential. However, succeeding in the gig economy requires more than just skills in your chosen field \\u2013 it requires mastering the art of freelancing. Whether you\'re a seasoned freelancer or just starting your journey, here are some tips to help you thrive in the competitive landscape of freelancing:<\\/p><p>\\u00a0<\\/p><p><strong>Define Your Niche:<\\/strong> Identify your strengths, passions, and areas of expertise. By specializing in a niche, you can position yourself as an expert in your field and attract high-paying clients who value your unique skills.<\\/p><p>\\u00a0<\\/p><p><strong>Build Your Brand:<\\/strong> Invest in creating a strong personal brand that reflects your values, personality, and professional identity. This includes designing a professional website, crafting a compelling portfolio, and establishing a strong presence on social media platforms relevant to your industry.<\\/p><p><br \\/><strong>Network Strategically:<\\/strong> Networking is key to success in freelancing. Attend industry events, join online communities, and connect with fellow freelancers and potential clients. Building genuine relationships and nurturing your network can lead to valuable collaborations and opportunities.<\\/p><p>\\u00a0<\\/p><p><strong>Hone Your Skills:<\\/strong> Continuous learning and improvement are essential in freelancing. Stay updated on industry trends, technologies, and best practices relevant to your field. Invest in online courses, workshops, and certifications to enhance your skills and stay ahead of the competition.<\\/p><p>\\u00a0<\\/p><p><strong>Set Clear Goals:<\\/strong> Define clear and achievable goals for your freelance career, whether it\'s increasing your income, expanding your client base, or launching a new service. Break down your goals into actionable steps and create a roadmap to track your progress.<\\/p><p>\\u00a0<\\/p><p><strong>Manage Your Finances:<\\/strong> Freelancing comes with financial uncertainties, so it\'s crucial to manage your finances wisely. Keep track of your income and expenses, set aside savings for taxes and emergencies, and consider investing in retirement accounts and insurance plans to secure your financial future.<\\/p><p>\\u00a0<\\/p><p><strong>Prioritize Self-Care:<\\/strong> Freelancing can be demanding, both mentally and physically. Prioritize self-care by establishing boundaries, maintaining a healthy work-life balance, and taking regular breaks to rest and recharge. Remember that your well-being is essential for sustained success.<\\/p><p>\\u00a0<\\/p><p><strong>Provide Exceptional Service:<\\/strong> Delivering exceptional service and exceeding client expectations is key to building a successful freelance business. Communicate clearly, meet deadlines, and always strive for excellence in your work. Happy clients are more likely to recommend you to others and become repeat customers.<\\/p><p>\\u00a0<\\/p><p>By implementing these tips and mastering the art of freelancing, you can unlock your full potential, build a thriving freelance business, and achieve long-term success in the gig economy. Embrace the opportunities, stay resilient in the face of challenges, and continue to evolve and grow as a freelancer. Your journey to success starts now!<\\/p>\",\"image\":\"66bdab484fa151723706184.png\"}', '2024-08-15 07:16:24', '2025-01-13 09:20:17'),
(198, 'blog.element', '{\"title\":\"A Guide to Finding Quick Talent for Small Projects\",\"description\":\"<p>In today\'s fast-paced world, freelancing has emerged as a popular choice for many professionals seeking flexibility, autonomy, and unlimited earning potential. However, succeeding in the gig economy requires more than just skills in your chosen field \\u2013 it requires mastering the art of freelancing. Whether you\'re a seasoned freelancer or just starting your journey, here are some tips to help you thrive in the competitive landscape of freelancing:<\\/p><p>\\u00a0<\\/p><p><strong>Define Your Niche:<\\/strong> Identify your strengths, passions, and areas of expertise. By specializing in a niche, you can position yourself as an expert in your field and attract high-paying clients who value your unique skills.<\\/p><p>\\u00a0<\\/p><p><strong>Build Your Brand:<\\/strong> Invest in creating a strong personal brand that reflects your values, personality, and professional identity. This includes designing a professional website, crafting a compelling portfolio, and establishing a strong presence on social media platforms relevant to your industry.<\\/p><p><br \\/><strong>Network Strategically:<\\/strong> Networking is key to success in freelancing. Attend industry events, join online communities, and connect with fellow freelancers and potential clients. Building genuine relationships and nurturing your network can lead to valuable collaborations and opportunities.<\\/p><p>\\u00a0<\\/p><p><strong>Hone Your Skills:<\\/strong> Continuous learning and improvement are essential in freelancing. Stay updated on industry trends, technologies, and best practices relevant to your field. Invest in online courses, workshops, and certifications to enhance your skills and stay ahead of the competition.<\\/p><p>\\u00a0<\\/p><p><strong>Set Clear Goals:<\\/strong> Define clear and achievable goals for your freelance career, whether it\'s increasing your income, expanding your client base, or launching a new service. Break down your goals into actionable steps and create a roadmap to track your progress.<\\/p><p>\\u00a0<\\/p><p><strong>Manage Your Finances:<\\/strong> Freelancing comes with financial uncertainties, so it\'s crucial to manage your finances wisely. Keep track of your income and expenses, set aside savings for taxes and emergencies, and consider investing in retirement accounts and insurance plans to secure your financial future.<\\/p><p>\\u00a0<\\/p><p><strong>Prioritize Self-Care:<\\/strong> Freelancing can be demanding, both mentally and physically. Prioritize self-care by establishing boundaries, maintaining a healthy work-life balance, and taking regular breaks to rest and recharge. Remember that your well-being is essential for sustained success.<\\/p><p>\\u00a0<\\/p><p><strong>Provide Exceptional Service:<\\/strong> Delivering exceptional service and exceeding client expectations is key to building a successful freelance business. Communicate clearly, meet deadlines, and always strive for excellence in your work. Happy clients are more likely to recommend you to others and become repeat customers.<\\/p><p>\\u00a0<\\/p><p>By implementing these tips and mastering the art of freelancing, you can unlock your full potential, build a thriving freelance business, and achieve long-term success in the gig economy. Embrace the opportunities, stay resilient in the face of challenges, and continue to evolve and grow as a freelancer. Your journey to success starts now!<\\/p>\",\"image\":\"66bdbe4ae6da21723711050.png\"}', '2024-08-15 08:37:30', '2025-01-13 09:20:03'),
(199, 'blog.element', '{\"title\":\"How Micro Job Platforms Revolutionize Small Business Outsourcing\",\"description\":\"<p>In today\'s fast-paced world, freelancing has emerged as a popular choice for many professionals seeking flexibility, autonomy, and unlimited earning potential. However, succeeding in the gig economy requires more than just skills in your chosen field \\u2013 it requires mastering the art of freelancing. Whether you\'re a seasoned freelancer or just starting your journey, here are some tips to help you thrive in the competitive landscape of freelancing:<\\/p><p>\\u00a0<\\/p><p><strong>Define Your Niche:<\\/strong> Identify your strengths, passions, and areas of expertise. By specializing in a niche, you can position yourself as an expert in your field and attract high-paying clients who value your unique skills.<\\/p><p>\\u00a0<\\/p><p><strong>Build Your Brand:<\\/strong> Invest in creating a strong personal brand that reflects your values, personality, and professional identity. This includes designing a professional website, crafting a compelling portfolio, and establishing a strong presence on social media platforms relevant to your industry.<\\/p><p><br \\/><strong>Network Strategically:<\\/strong> Networking is key to success in freelancing. Attend industry events, join online communities, and connect with fellow freelancers and potential clients. Building genuine relationships and nurturing your network can lead to valuable collaborations and opportunities.<\\/p><p>\\u00a0<\\/p><p><strong>Hone Your Skills:<\\/strong> Continuous learning and improvement are essential in freelancing. Stay updated on industry trends, technologies, and best practices relevant to your field. Invest in online courses, workshops, and certifications to enhance your skills and stay ahead of the competition.<\\/p><p>\\u00a0<\\/p><p><strong>Set Clear Goals:<\\/strong> Define clear and achievable goals for your freelance career, whether it\'s increasing your income, expanding your client base, or launching a new service. Break down your goals into actionable steps and create a roadmap to track your progress.<\\/p><p>\\u00a0<\\/p><p><strong>Manage Your Finances:<\\/strong> Freelancing comes with financial uncertainties, so it\'s crucial to manage your finances wisely. Keep track of your income and expenses, set aside savings for taxes and emergencies, and consider investing in retirement accounts and insurance plans to secure your financial future.<\\/p><p>\\u00a0<\\/p><p><strong>Prioritize Self-Care:<\\/strong> Freelancing can be demanding, both mentally and physically. Prioritize self-care by establishing boundaries, maintaining a healthy work-life balance, and taking regular breaks to rest and recharge. Remember that your well-being is essential for sustained success.<\\/p><p>\\u00a0<\\/p><p><strong>Provide Exceptional Service:<\\/strong> Delivering exceptional service and exceeding client expectations is key to building a successful freelance business. Communicate clearly, meet deadlines, and always strive for excellence in your work. Happy clients are more likely to recommend you to others and become repeat customers.<\\/p><p>\\u00a0<\\/p><p>By implementing these tips and mastering the art of freelancing, you can unlock your full potential, build a thriving freelance business, and achieve long-term success in the gig economy. Embrace the opportunities, stay resilient in the face of challenges, and continue to evolve and grow as a freelancer. Your journey to success starts now!<\\/p>\",\"image\":\"66bdbe6b20e4d1723711083.png\"}', '2024-08-15 08:38:03', '2025-01-13 09:19:36'),
(200, 'faq.element', '{\"question\":\"What is a microjob freelancing platform?\",\"answer\":\"<p>A microjob freelancing platform is an online marketplace where freelancers offer small, specific tasks or services, known as microjobs, to clients or businesses. These tasks can range from graphic design and writing to virtual assistance and programming.<\\/p>\"}', '2024-08-17 10:56:05', '2024-08-17 10:56:05'),
(201, 'faq.element', '{\"question\":\"How does a microjob freelancing platform work?\",\"answer\":\"<p>Freelancers create profiles showcasing their skills and expertise, and clients post their job requirements. Freelancers then bid on or apply for jobs, and clients select the freelancer they want to hire based on factors such as price, skills, and reviews. Once the job is completed, payment is typically released through the platform.<\\/p>\"}', '2024-08-17 10:56:52', '2024-08-17 10:56:52'),
(202, 'faq.element', '{\"question\":\"What types of services are offered on a microjob freelancing platform?\",\"answer\":\"<p>Microjob freelancing platforms typically offer a wide range of services, including graphic design, writing and translation, digital marketing, programming and tech, administrative support, video editing, and more. The diversity of services allows businesses and individuals to find the expertise they need for various projects.<\\/p>\"}', '2024-08-17 10:57:35', '2024-08-17 10:57:35'),
(203, 'faq.element', '{\"question\":\"How do I find reliable freelancers on a microjob freelancing platform?\",\"answer\":\"<p>To find reliable freelancers, clients can review freelancers\' profiles, which often include ratings, reviews, and portfolios of past work. Clients can also communicate with freelancers before hiring them to discuss project details and ensure they\'re the right fit for the job.<\\/p>\"}', '2024-08-17 10:58:20', '2024-08-17 10:58:20'),
(204, 'faq.element', '{\"question\":\"What are the benefits of using a microjob freelancing platform?\",\"answer\":\"<p>Microjob freelancing platforms offer several benefits, including access to a global talent pool, flexibility in hiring freelancers for specific tasks, cost-effectiveness compared to traditional hiring methods, and streamlined project management through platform features such as messaging, file sharing, and payment processing.<\\/p>\"}', '2024-08-17 10:59:17', '2024-08-17 10:59:17'),
(205, 'faq.element', '{\"question\":\"How do payments work on a microjob freelancing platform?\",\"answer\":\"<p>Payments on microjob freelancing platforms are typically processed securely through the platform\'s payment system. Clients may fund milestones or escrow payments before the project begins, and freelancers receive payment once the client approves the completed work. Payment methods may include credit\\/debit cards, PayPal, or direct bank transfers.<\\/p>\"}', '2024-08-17 11:06:20', '2024-08-17 11:06:20'),
(206, 'faq.element', '{\"question\":\"Are there any fees associated with using a microjob freelancing platform?\",\"answer\":\"<p>While many microjob freelancing platforms allow freelancers to create profiles and bid on jobs for free, they often charge fees for successful transactions. These fees may include a percentage of the project\'s value or a flat fee per transaction. Additionally, clients may incur fees for payment processing or premium features, such as promoting job listings.<\\/p>\"}', '2024-08-17 11:07:19', '2024-08-17 11:07:19'),
(207, 'counters.content', '{\"section_heading_subtitle\":\"Statistics\",\"section_heading_title\":\"Trusted by Thousands of Creatives Worldwide\"}', '2025-01-13 07:24:02', '2025-01-13 07:24:02'),
(208, 'blog.element', '{\"title\":\"Why Micro Job Platforms Are the Future of Business Efficiency\",\"description\":\"<p>In today\'s fast-paced world, freelancing has emerged as a popular choice for many professionals seeking flexibility, autonomy, and unlimited earning potential. However, succeeding in the gig economy requires more than just skills in your chosen field \\u2013 it requires mastering the art of freelancing. Whether you\'re a seasoned freelancer or just starting your journey, here are some tips to help you thrive in the competitive landscape of freelancing:<\\/p><p>\\u00a0<\\/p><p><strong>Define Your Niche:<\\/strong> Identify your strengths, passions, and areas of expertise. By specializing in a niche, you can position yourself as an expert in your field and attract high-paying clients who value your unique skills.<\\/p><p>\\u00a0<\\/p><p><strong>Build Your Brand:<\\/strong> Invest in creating a strong personal brand that reflects your values, personality, and professional identity. This includes designing a professional website, crafting a compelling portfolio, and establishing a strong presence on social media platforms relevant to your industry.<\\/p><p><br \\/><strong>Network Strategically:<\\/strong> Networking is key to success in freelancing. Attend industry events, join online communities, and connect with fellow freelancers and potential clients. Building genuine relationships and nurturing your network can lead to valuable collaborations and opportunities.<\\/p><p>\\u00a0<\\/p><p><strong>Hone Your Skills:<\\/strong> Continuous learning and improvement are essential in freelancing. Stay updated on industry trends, technologies, and best practices relevant to your field. Invest in online courses, workshops, and certifications to enhance your skills and stay ahead of the competition.<\\/p><p>\\u00a0<\\/p><p><strong>Set Clear Goals:<\\/strong> Define clear and achievable goals for your freelance career, whether it\'s increasing your income, expanding your client base, or launching a new service. Break down your goals into actionable steps and create a roadmap to track your progress.<\\/p><p>\\u00a0<\\/p><p><strong>Manage Your Finances:<\\/strong> Freelancing comes with financial uncertainties, so it\'s crucial to manage your finances wisely. Keep track of your income and expenses, set aside savings for taxes and emergencies, and consider investing in retirement accounts and insurance plans to secure your financial future.<\\/p><p>\\u00a0<\\/p><p><strong>Prioritize Self-Care:<\\/strong> Freelancing can be demanding, both mentally and physically. Prioritize self-care by establishing boundaries, maintaining a healthy work-life balance, and taking regular breaks to rest and recharge. Remember that your well-being is essential for sustained success.<\\/p><p>\\u00a0<\\/p><p><strong>Provide Exceptional Service:<\\/strong> Delivering exceptional service and exceeding client expectations is key to building a successful freelance business. Communicate clearly, meet deadlines, and always strive for excellence in your work. Happy clients are more likely to recommend you to others and become repeat customers.<\\/p><p>\\u00a0<\\/p><p>By implementing these tips and mastering the art of freelancing, you can unlock your full potential, build a thriving freelance business, and achieve long-term success in the gig economy. Embrace the opportunities, stay resilient in the face of challenges, and continue to evolve and grow as a freelancer. Your journey to success starts now!<\\/p>\",\"image\":\"67853103dae7b1736782083.png\"}', '2025-01-13 09:28:03', '2025-01-13 09:28:04'),
(209, 'blog.element', '{\"title\":\"How to Build a Winning Profile and Attract More Jobs\",\"description\":\"<p>In today\'s fast-paced world, freelancing has emerged as a popular choice for many professionals seeking flexibility, autonomy, and unlimited earning potential. However, succeeding in the gig economy requires more than just skills in your chosen field \\u2013 it requires mastering the art of freelancing. Whether you\'re a seasoned freelancer or just starting your journey, here are some tips to help you thrive in the competitive landscape of freelancing:<\\/p><p>\\u00a0<\\/p><p><strong>Define Your Niche:<\\/strong> Identify your strengths, passions, and areas of expertise. By specializing in a niche, you can position yourself as an expert in your field and attract high-paying clients who value your unique skills.<\\/p><p>\\u00a0<\\/p><p><strong>Build Your Brand:<\\/strong> Invest in creating a strong personal brand that reflects your values, personality, and professional identity. This includes designing a professional website, crafting a compelling portfolio, and establishing a strong presence on social media platforms relevant to your industry.<\\/p><p><br \\/><strong>Network Strategically:<\\/strong> Networking is key to success in freelancing. Attend industry events, join online communities, and connect with fellow freelancers and potential clients. Building genuine relationships and nurturing your network can lead to valuable collaborations and opportunities.<\\/p><p>\\u00a0<\\/p><p><strong>Hone Your Skills:<\\/strong> Continuous learning and improvement are essential in freelancing. Stay updated on industry trends, technologies, and best practices relevant to your field. Invest in online courses, workshops, and certifications to enhance your skills and stay ahead of the competition.<\\/p><p>\\u00a0<\\/p><p><strong>Set Clear Goals:<\\/strong> Define clear and achievable goals for your freelance career, whether it\'s increasing your income, expanding your client base, or launching a new service. Break down your goals into actionable steps and create a roadmap to track your progress.<\\/p><p>\\u00a0<\\/p><p><strong>Manage Your Finances:<\\/strong> Freelancing comes with financial uncertainties, so it\'s crucial to manage your finances wisely. Keep track of your income and expenses, set aside savings for taxes and emergencies, and consider investing in retirement accounts and insurance plans to secure your financial future.<\\/p><p>\\u00a0<\\/p><p><strong>Prioritize Self-Care:<\\/strong> Freelancing can be demanding, both mentally and physically. Prioritize self-care by establishing boundaries, maintaining a healthy work-life balance, and taking regular breaks to rest and recharge. Remember that your well-being is essential for sustained success.<\\/p><p>\\u00a0<\\/p><p><strong>Provide Exceptional Service:<\\/strong> Delivering exceptional service and exceeding client expectations is key to building a successful freelance business. Communicate clearly, meet deadlines, and always strive for excellence in your work. Happy clients are more likely to recommend you to others and become repeat customers.<\\/p><p>\\u00a0<\\/p><p>By implementing these tips and mastering the art of freelancing, you can unlock your full potential, build a thriving freelance business, and achieve long-term success in the gig economy. Embrace the opportunities, stay resilient in the face of challenges, and continue to evolve and grow as a freelancer. Your journey to success starts now!<\\/p>\",\"image\":\"6785317ddb44f1736782205.png\"}', '2025-01-13 09:29:13', '2025-01-13 09:30:06'),
(210, 'blog.element', '{\"title\":\"How to Maximize Your Earnings on Micro Job Platforms\",\"description\":\"<p>In today\'s fast-paced world, freelancing has emerged as a popular choice for many professionals seeking flexibility, autonomy, and unlimited earning potential. However, succeeding in the gig economy requires more than just skills in your chosen field \\u2013 it requires mastering the art of freelancing. Whether you\'re a seasoned freelancer or just starting your journey, here are some tips to help you thrive in the competitive landscape of freelancing:<\\/p><p>\\u00a0<\\/p><p><strong>Define Your Niche:<\\/strong> Identify your strengths, passions, and areas of expertise. By specializing in a niche, you can position yourself as an expert in your field and attract high-paying clients who value your unique skills.<\\/p><p>\\u00a0<\\/p><p><strong>Build Your Brand:<\\/strong> Invest in creating a strong personal brand that reflects your values, personality, and professional identity. This includes designing a professional website, crafting a compelling portfolio, and establishing a strong presence on social media platforms relevant to your industry.<\\/p><p><br \\/><strong>Network Strategically:<\\/strong> Networking is key to success in freelancing. Attend industry events, join online communities, and connect with fellow freelancers and potential clients. Building genuine relationships and nurturing your network can lead to valuable collaborations and opportunities.<\\/p><p>\\u00a0<\\/p><p><strong>Hone Your Skills:<\\/strong> Continuous learning and improvement are essential in freelancing. Stay updated on industry trends, technologies, and best practices relevant to your field. Invest in online courses, workshops, and certifications to enhance your skills and stay ahead of the competition.<\\/p><p>\\u00a0<\\/p><p><strong>Set Clear Goals:<\\/strong> Define clear and achievable goals for your freelance career, whether it\'s increasing your income, expanding your client base, or launching a new service. Break down your goals into actionable steps and create a roadmap to track your progress.<\\/p><p>\\u00a0<\\/p><p><strong>Manage Your Finances:<\\/strong> Freelancing comes with financial uncertainties, so it\'s crucial to manage your finances wisely. Keep track of your income and expenses, set aside savings for taxes and emergencies, and consider investing in retirement accounts and insurance plans to secure your financial future.<\\/p><p>\\u00a0<\\/p><p><strong>Prioritize Self-Care:<\\/strong> Freelancing can be demanding, both mentally and physically. Prioritize self-care by establishing boundaries, maintaining a healthy work-life balance, and taking regular breaks to rest and recharge. Remember that your well-being is essential for sustained success.<\\/p><p>\\u00a0<\\/p><p><strong>Provide Exceptional Service:<\\/strong> Delivering exceptional service and exceeding client expectations is key to building a successful freelance business. Communicate clearly, meet deadlines, and always strive for excellence in your work. Happy clients are more likely to recommend you to others and become repeat customers.<\\/p><p>\\u00a0<\\/p><p>By implementing these tips and mastering the art of freelancing, you can unlock your full potential, build a thriving freelance business, and achieve long-term success in the gig economy. Embrace the opportunities, stay resilient in the face of challenges, and continue to evolve and grow as a freelancer. Your journey to success starts now!<\\/p>\",\"image\":\"678531a267c361736782242.png\"}', '2025-01-13 09:30:42', '2025-01-13 09:30:42');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `post_balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_by` int UNSIGNED NOT NULL DEFAULT '0',
  `balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `kyc_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kc` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: KYC unconfirmed, 2: KYC pending, 1: KYC confirmed',
  `ec` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: email unconfirmed, 1: email confirmed',
  `sc` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: mobile unconfirmed, 1: mobile confirmed',
  `ver_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tc` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '0: 2fa unconfirmed, 1: 2fa confirmed',
  `tsc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ban_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kyf_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `freelancer_status` tinyint UNSIGNED NOT NULL DEFAULT '5' COMMENT '0 -> rejected, 1 -> active, 2 -> pending, 3 -> banned, 5 -> not freelancer',
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skills` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `freelancer_rejection_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `freelancer_ban_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `freelancer_applied_at` timestamp NULL DEFAULT NULL COMMENT 'freelancer application submission date'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint UNSIGNED NOT NULL,
  `method_id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `after_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `withdraw_information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 => success, 2 => pending, 3 => cancel  ',
  `admin_feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `fixed_charge` decimal(28,8) DEFAULT '0.00000000',
  `rate` decimal(28,8) DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) DEFAULT NULL,
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guideline` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assigned_jobs`
--
ALTER TABLE `assigned_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_types`
--
ALTER TABLE `file_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `file_types_type_unique` (`type`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `freelancer_reviews`
--
ALTER TABLE `freelancer_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_categories`
--
ALTER TABLE `job_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_categories_name_unique` (`name`);

--
-- Indexes for table `job_conversations`
--
ALTER TABLE `job_conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_posts`
--
ALTER TABLE `job_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_subcategories`
--
ALTER TABLE `job_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_subcategories_name_unique` (`name`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plugins`
--
ALTER TABLE `plugins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_data`
--
ALTER TABLE `site_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assigned_jobs`
--
ALTER TABLE `assigned_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_types`
--
ALTER TABLE `file_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `freelancer_reviews`
--
ALTER TABLE `freelancer_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_categories`
--
ALTER TABLE `job_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_conversations`
--
ALTER TABLE `job_conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_posts`
--
ALTER TABLE `job_posts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_subcategories`
--
ALTER TABLE `job_subcategories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plugins`
--
ALTER TABLE `plugins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_data`
--
ALTER TABLE `site_data`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
