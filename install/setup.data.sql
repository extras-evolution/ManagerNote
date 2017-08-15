--
-- Структура таблицы `{PREFIX}manager_note`
--

CREATE TABLE IF NOT EXISTS `{PREFIX}manager_note` (
	`id` int(11) NOT NULL AUTO_INCREMENT, 
	`note_text` text, 
	`time_add` int(12) NOT NULL,
	`manager` int(5) NOT NULL, PRIMARY KEY (`id`))
	ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;