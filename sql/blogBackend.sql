CREATE TABLE IF NOT EXISTS `blog_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_topic` text NOT NULL,
  `focus_keyword` text NOT NULL,
  `action_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `target_submission_date` datetime NOT NULL,
  `payout_type` varchar(200) NOT NULL,
  `payout_amount` float(10,5) NOT NULL,
  `editor_id` int(11) NOT NULL,
  `writer_id` int(11) NOT NULL,
  `auditor_id` int(11) NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `blog_task` ADD INDEX(`editor_id`);
ALTER TABLE `blog_task` ADD INDEX(`writer_id`);
ALTER TABLE `blog_task` ADD INDEX(`auditor_id`);

CREATE TABLE IF NOT EXISTS `blog_task_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `document_version` int(11) NOT NULL,
  `document_name` text NOT NULL,
  `document_link` text NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `blog_task_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `action` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `action_by` int(11) NOT NULL,
  `reason` text NOT NULL,
  `comments` text NOT NULL,
  `action_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `blog_task_action` ADD INDEX(`action`);
ALTER TABLE `blog_task_action` ADD INDEX(`status`);

CREATE TABLE IF NOT EXISTS `blog_task_flow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(200) NOT NULL,
  `assigned_to` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `blog_task_flow` (`id`, `action`, `assigned_to`, `status`) VALUES
(1, 'assigned_by_editor', 'WRITER', 'ASSIGNED'),
(2, 'submitted_by_writer', 'EDITOR', 'SUBMITTED'),
(3, 'approved_by_editor', 'AUDITOR', 'APPROVED'),
(4, 'reassigned_by_editor', 'WRITER', 'RE-ASSIGNED'),
(5, 'published_by_auditor', '', 'PUBLISHED'),
(6, 'rejected_by_auditor', '', 'REJECTED'),
(7, 'reassigned_by_auditor', 'EDITOR', 'RE-ASSIGNED');

INSERT INTO `med_role` (`id`, `role_name`, `role_description`, `created_on`, `created_by`, `updated_on`, `updated_by`) VALUES (NULL, 'Blog Auditor', 'blog auditor', CURRENT_TIMESTAMP, NULL, NULL, NULL), (NULL, 'Blog Editor', 'blog editor', CURRENT_TIMESTAMP, NULL, NULL, NULL), (NULL, 'Blog Writer', 'blog writer', CURRENT_TIMESTAMP, NULL, NULL, NULL);