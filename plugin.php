<?php
class Plugin implements SenzuPlugin
{
    public function play($data, $config = [])
    {
        // Le script qui se trouve ici ne s'execute uniquement qu'une fois que l'utilisateur clique sur votre module, après avoir scanné l'objet.

       /*
        data : résultats du formulaire (configuration du module)
        config : informations sur le scan et l'objet qui a été scanné
       */
        var_dump($data, $config);

        echo '<p>Hello Senzu</p>';

    }
}