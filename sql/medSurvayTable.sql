CREATE TABLE IF NOT EXISTS `med_prime_survey` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `blog_category` varchar(100) NOT NULL,
  `survey_question` text NOT NULL,
  `survey_answers` varchar(50) NOT NULL,
  `submitted_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
);