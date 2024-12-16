- authentification jwt
- si identifié get all users


ENTITY
- entité profile (displayname , user ,image)
- [x] invitation ( author, profile , date;event, status ( accepté, refusé, en attente deault)
- [x] /api create/event (name, lieu type ;description,date non dépassé , date de fin suerieur a la date de début, Profile auteur de l'event, statut public ou privé, et un type de lieu public ou privé , state ( on schedule toujours prevu ou caceled ,contributions;suggestions, image )


REQUEETE

- [x] recupere la liste des events publics
- [x] recuperer la liste des events privés auxuqels on est ivnités
-  event privé : recuperer les participants d'un evenement si on est dans cet event ( participant ou organisateur ) voir qui a repondu et pas repondu etc
- event publ
-  event privé : recuperer les participants d'un evenement si on est dans cet event ( participant ou organisateur ) voir qui a repondu et ic recuperé les participants si on est organisateur
- recuperer la liste de ses invitations
- liste des evenements auxxquel on s'est inscrite (agenda)
- marqué comme annulé un event
- modifier les dates d'un evenement mais faire attention aux dates
-

REGLE
- si event annulé on peu tpas le rejoindre
- ajouter des personnes a un event privé  ayant un compte si event non annulé
- on peut accepter ou  refuser uen invitation que jus'qua la date de debut , si event passé on  met comme refuser
- on peut pas changer le status d'une invitation passé
- si un event est annulé on peut plus le rejoindre ni accepter l'invitation


Evenemnt dans un lieu privé :
- organisateur creer des suggestions
- modifier ou supprimer une prise en charge ( editer le status de la sugegstion)
  supprimer tout court une pris ene charge
- prise en charge d'une sugegstion
- prise en charge
  Contribution ( event, suggestions,prises en charge,type)
  Prise en charge ( profile, liste de suggestions(null) , description ,event )
  Suggestion ( type , description , status = [prise en charge, a prendre en charge ], profile ,event )

contirbutions = [ suggestions ,  prises en chagre]


MAAXI BONUS : ajoute rune personne en admin