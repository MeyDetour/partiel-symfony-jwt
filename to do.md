- authentification jwt
- si identifié get all users


ENTITY
- entité profile (displayname , user ,image)
- [x] invitation ( author, profile , date;event, status ( accepté, refusé, en attente deault)
-  /api create/event (name, lieu type ;description,date non dépassé , date de fin suerieur a la date de début, Profile auteur de l'event, statut public ou privé, et un type de lieu public ou privé , state ( on schedule toujours prevu ou caceled ,contributions;suggestions, image )


REQUEETE

- [x] recupere la liste des events publics
- [x] recuperer la liste des events privés auxuqels on est ivnités
- [x] event privé : recuperer les participants d'un evenement si on est dans cet event ( participant ou organisateur ) voir qui a repondu et pas repondu etc
- [x] recuperer la liste de ses invitations
- [x] liste des evenements auxxquel on s'est inscrite (agenda)
- [x] marqué comme annulé un event
- [x] modifier les dates d'un evenement mais faire attention aux dates
-

REGLE
- [x] si event annulé on peu tpas le rejoindre
- [x] ajouter des personnes a un event privé  ayant un compte si event non annulé
- [x] on peut accepter ou  refuser uen invitation que jus'qua la date de debut , si event passé on  met comme refuser
- [x] on peut pas changer le status d'une invitation passé
- [x] si un event est annulé on peut plus le rejoindre ni accepter l'invitation


Evenemnt dans un lieu privé :
- creer des contributions 
-  ajouter a ces contributions des suggetsions 
-  ajouter a ces contributions des prise en charge ( rebelle ))
-  prendre en charge une suggestion , dé prendre en charge une suggestion 
-  supprimer une prise en charge rebelle 
- supprimer une suggestion 
- editer une suggestion
  supprimer tout court une pris ene charge
- Contribution ( event, suggestions,prises en charge,desstiption (type) ))
- Prise en charge ( profile,  description , contribution )
- Suggestion (  description , status = [prise en charge, a prendre en charge ], profile qui s'en occupe ,contribution )
 


+ documentations
+ remove unused improtation
+ document code
+ features indication file and function

MAAXI BONUS : ajoute rune personne en admin