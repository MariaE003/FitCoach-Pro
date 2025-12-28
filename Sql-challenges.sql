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
/*ch2 - le nombre des reservation par moi*/
SELECT u.nom,u.prenom, month(se.date_seance) as mois,date_format(se.date_seance,"%Y") as date,COUNT(*) as nbr from users u 
INNER JOIN sportifs s on s.user_id=u.id 
INNER JOIN reservations r on r.sportif_id=s.user_id 
INNER JOIN seances se on se.id=r.seance_id 
GROUP by mois,u.id ,date
ORDER BY nbr DESC;

Challenge 3:
/**/

SELECT * FROM seances se1
join seances se2 on se1.coach_id=se2.coach_id
and se2.id<se1.id
and se1.heure<date_add(se2.heure,interval se2.duree minute)
and se2.heure<date_add(se1.heure,interval se1.duree minute)

/**/
SELECT se1.coach_id,se1.date_seance,se1.heure,date_add(se1.heure,interval se1.duree minute),se1.id FROM seances se1
join seances se2 on se1.coach_id=se2.coach_id
and se2.id<se1.id
and se1.heure<date_add(se2.heure,interval se2.duree minute)
and se2.heure<date_add(se1.heure,interval se1.duree minute)

Challenge 4: