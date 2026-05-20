# IT_Eksamen_2026

I denne oppgaven har jeg satt opp en server lokalt på egen maskin ved bruk av XAMPP.
Dette ble gjort fordi den virtuelle maskinen som ble satt opp for bruk ikke egnet seg for arbeid, jeg har en liste med problemstillinger.

I denne oppgaven har jeg laget en ganske enkel webapplikasjon for stemming, hvor brukeren kan velge et land og gi en stemme. 
Alt er koblet opp mot en database, så alt som skjer på nettsiden blir lagret og brukt videre.

fundamentale ideen bak løsningen er at når en bruker går inn på siden, så kan personen velge et land og trykke på stemme. 
Når det skjer, blir IP-adressen brukt som en ID for å passe på at samme bruker ikke stemmer flere ganger. 
Det er ikke en perfekt løsning, men det funker greit til en sånn type oppgave. - IKKE i praksis, men her og nå som skole oppgave.

Dataene blir lagret i flere tabeller i databasen, der hver tabell har sin egen oppgave. 
For eksempel en tabell for land, en for brukere og en for selve stemmene. 
Disse er koblet sammen sånn at det er lett å hente ut resultatene.

Når det gjelder resultater, så er det laget en egen side som viser hvor mange stemmer hvert land har fått, og også prosent av totalen. 
Det går også an å velge år, så man kan se gamle resultater. Dette gjør at løsningen ikke bare viser én ting, men kan brukes litt mer fleksibelt.

For å teste at systemet faktisk funker under litt belastning, lagde jeg også et script som legger inn mange tilfeldige stemmer automatisk. 
Det gjør det lettere å se om resultat-siden fungerer og om databasen håndterer mye data.

Hele greia kjører lokalt på min egen PC ved bruk av XAMPP, som gir meg webserver og database uten at jeg trenger en egen server. 
Selv om det er lokalt, er det satt opp på en måte som ligner på hvordan man ville gjort det på en ekte server.

Alt i alt er løsningen ganske bra og enkel, men den dekker det som trengs: man kan stemme, lagre data, hente ut resultater og teste systemet.
