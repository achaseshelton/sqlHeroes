## SQL Heroes  
  
User wants to maintain a databse of heroes. Database should include a hero name, about me, biography, list of abilities, and relationsphips for the heroes.  

#### Requirements
1. Create a connection to the local Database using PHP and view the database.
2. Use the supplied .sql Database.
3. Use a minimum of four CRUD operations.
4. Display all superheroes as a list showing their name, about me, and abilities.


#### CRUD Functions

1. Create
    - Create a hero with a name, about me, biography and give them an ability.
        - Insert into the hero table
            - Set the name to be the name given in the url
            - Set the about me to be the about me given in the url
            - Set the biography to be the about me give in the url
        - Insert the heros ability in the abilities table if given.
            - in the abilites table hero id should be the id from the hero table
            - the ability id should be the id given in the url.

2. Read
    - Display the information from the given id in the url.
        - Get the name, about me, and biography from the url in the heroes table
        - get the abilities from the ability table where the id is eqaual to the hero id.

3. Read All
    - Display the information for all the heroes.
        - Get all the information for the heroes table.
        - Get the abilities information from the abilities table for each hero.

4. Update
    - Update hero information in the heroes table.
        - Change the name with the name given in the url if it exsists
        - Change the about_me with the name given in the url it it exsists
        - Change the biograhy with the biography given in the url if it exsists

5. Delete
    - Delete the hero from the heroes table.
        - Delete the hero from the heroes table from the id supplied in the url.
