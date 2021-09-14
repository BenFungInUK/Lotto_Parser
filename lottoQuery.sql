/* SELECT * FROM Lotto.lotto_result where number_1 = and number_2 = and number_3 = and number_4 = and number_5 = and number_1 -- = and ;
*/
/* --Check duplicated draw
SELECT t1.*, t2.total FROM Lotto.lotto_result t1 inner join (
SELECT number_1, number_2,number_3, number_4, number_5, COUNT(1) as total FROM Lotto.lotto_result group by number_1, number_2,number_3, number_4, number_5) t2
ON t1.number_1 = t2.number_1 AND t1.number_2 = t2.number_2 AND t1.number_3 = t2.number_3 AND t1.number_4 = t2.number_4 AND t1.number_5 = t2.number_5
WHERE t2.total > 1
;*/
-- Check Result exists
-- SELECT * FROM Lotto.lotto_result where number_1 = 14 and number_2 = 16 and number_3 = 19 and number_4 = 23 and number_5 = 28 and number_special = 11;
-- Check Result exists without thunderball
-- SELECT * FROM Lotto.lotto_result where number_1 = 14 and number_2 = 16 and number_3 = 19 and number_4 = 23 and number_5 = 28 

-- 1st  500000  1/39C5  = 0.00017368%  (575757) 
-- 2nd  5000    13/39C5 = 0.00225789%  (575757) 
-- 3rd  250     1/39C4  = 0.00121579%  (82251) 
-- 4th  100     13/39C4 = 0.01580527%
-- 5th  20      1/39C3  = 0.01094211%  (9,139)
-- 6th  10      13/39C3 = 0.14224751%
-- 7th  10      1/39C2  = 0.13495276%  (741)
-- 8th  5       1/39C1  = 2.56410256%  (39)
-- 9th  3       100%
-- 500000*0.00017368%+5000*0.00225789%+250*0.00121579%+100*0.01580527%+20*0.01094211%+10*0.14224751%+10*0.13495276%+5*2.56410256%+3 = 4.158252822 - 14 = -9.841747178 

UPDATE Lotto.lotto_count 
set Percentage = Count/2899*100;

UPDATE Lotto.lotto_count AS t
LEFT JOIN (
SELECT COUNT(1) as total, number_1 FROM Lotto.lotto_result group by number_1) t1 ON t.Number = t1.number_1 
LEFT JOIN (
SELECT COUNT(1) as total, number_2 FROM Lotto.lotto_result group by number_2) t2 ON t.Number = t2.number_2
LEFT JOIN (
SELECT COUNT(1) as total, number_3 FROM Lotto.lotto_result group by number_3) t3 ON t.Number = t3.number_3
LEFT JOIN (
SELECT COUNT(1) as total, number_4 FROM Lotto.lotto_result group by number_4) t4 ON t.Number = t4.number_4
LEFT JOIN (
SELECT COUNT(1) as total, number_5 FROM Lotto.lotto_result group by number_5) t5 ON t.Number = t5.number_5
SET Count = IFNULL(t1.total,0) + IFNULL(t2.total,0) + IFNULL(t3.total,0) + IFNULL(t4.total,0) + IFNULL(t5.total,0);

UPDATE Lotto.thunder_count AS t
LEFT JOIN (
SELECT COUNT(1) as total, number_special FROM Lotto.lotto_result group by number_special) t1 ON t.Number = t1.number_special
SET Count = t1.total;