drop view `view_companies`;
CREATE VIEW `view_companies`  AS
SELECT 
    COUNT(`a`.`id`) AS `agenttotal`,
    `a`.`companyid` AS `companyid`,
    `b`.`officename` AS `officename`,
    `c`.`username` AS `username`,
    `c`.`username4m` AS `username4m`,
    `c`.`originalpwd` AS `originalpwd`,
    `c`.`regtime` AS `regtime`,
    `c`.`status` AS `status`,
    `c`.`online` AS `online`,
    `b`.`street` AS `street`,
    `b`.`city` AS `city`,
    `b`.`state` AS `state`,
    `d`.`fullname` AS `counrty`,
    `b`.`payeename` AS `payeename`,
    `b`.`contactname` AS `contactname`,
    `b`.`man1stname` AS `man1stname`,
    `b`.`manlastname` AS `manlastname`,
    `b`.`manemail` AS `manemail`,
    `b`.`mancellphone` AS `mancellphone`
FROM
    (((`agents` `a`
    JOIN `companies` `b`)
    JOIN `accounts` `c`)
    JOIN `countries` `d`)
WHERE
    ((`a`.`companyid` = `b`.`id`)
        AND (`b`.`id` = `c`.`id`)
        AND (`b`.`country` = `d`.`abbr`))
GROUP BY `a`.`companyid`, `d`.`fullname`
UNION SELECT 
    0 AS `0`,
    `b`.`id` AS `companyid`,
    `b`.`officename` AS `officename`,
    `c`.`username` AS `username`,
    `c`.`username4m` AS `username4m`,
    `c`.`originalpwd` AS `originalpwd`,
    `c`.`regtime` AS `regtime`,
    `c`.`status` AS `status`,
    `c`.`online` AS `online`,
    `b`.`street` AS `street`,
    `b`.`city` AS `city`,
    `b`.`state` AS `state`,
    `d`.`fullname` AS `counrty`,
    `b`.`payeename` AS `payeename`,
    `b`.`contactname` AS `contactname`,
    `b`.`man1stname` AS `man1stname`,
    `b`.`manlastname` AS `manlastname`,
    `b`.`manemail` AS `manemail`,
    `b`.`mancellphone` AS `mancellphone`
FROM
    ((`companies` `b`
    JOIN `accounts` `c`)
    JOIN `countries` `d`)
WHERE
    ((`b`.`id` = `c`.`id`)
        AND (`b`.`country` = `d`.`abbr`)
        AND (NOT (`b`.`id` IN (SELECT DISTINCT
            `agents`.`companyid` AS `companyid`
        FROM
            `agents`))))