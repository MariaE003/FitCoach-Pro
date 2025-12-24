Challenge 1:
1 - SELECT c.user_id,COUNT(*) as total  FROM seances s INNER join coachs c on c.user_id=s.coach_id GROUP BY c.user_id

2 - SSELECT  c.user_id,COUNT(*) as total  FROM seances s INNER join coachs c on c.user_id=s.coach_id WHERE s.statut="reservee" GROUP BY c.user_id;

3 - SELECT s.coach_id,COUNT(r.id)*100 / COUNT(s.id) from reservations r INNER JOIN seances s on r.seance_id=s.id
INNER JOIN coachs c on c.user_id=s.coach_id
GROUP BY s.coach_id

4 - SELECT  coach_id,COUNT(*) as nbr FROM seances 
    GROUP by  coach_id
    HAVING nbr>=3

Challenge 2:
5 -