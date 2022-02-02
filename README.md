# Module Senzu 

## Initialisation
### Structure :
Votre module doit adopter la structure de base suivante :
> NomDuModule (dossier)
>
>> --> form.xml
>
>> --> plugin.php
>
>> --> script.html
>
>>  --> lang (dossier)
>>> --> fr.json
>>
>>> --> en.json

### fr/en.json
```json
{
  "title" : "Title",
  "chooseOne" : "Choose one !",
  "one":"choice 1",
  "two":"choice 2"
}
```

### Form.xml

```xml
<form>
    <!-- Groupe de champs  -->
    <fields>
        <!-- Champ de type texte (fieldset et input)  -->
        <field name="name" id="name" type="text">
            <!-- 
                Légende du fieldset. 
                "{{title}}" est la clé définie dans nos fichiers de langue (fr.json et en.js)
                Résultat : https://i.imgur.com/uIS7Gvk.png
            -->
            <label>{{title}}</label>
        </field>

        <!--
            Groupe d'input de type radio (type="choice")
            Résultat : https://i.imgur.com/ZrjFMl7.png
        -->
        <field name="radioForm" id="radioForm" required="true" type="choice">
            <label>{{chooseOne}}</label>
            <!-- Champs de type radio -->
            <option id="one">
                <label>{{one}}</label>
            </option>
            <option id="two">
                <label>{{two}}</label>
            </option>
        </field>

    </fields>

    <files>
        <!-- Fichiers de langue (Ne pas retirer) -->
        <file type="language" lang="fr" src="lang/fr.json"/>
        <file type="language" lang="en" src="lang/en.json"/>
    </files>
    <html>
        <block lang="fr">
            <body>
                <!-- Voir fichier script.html -->
                {{include(script.html)}}
            </body>
        </block>
    </html>
</form>
```
#### Résultat : 
![Module Config Interface](https://i.imgur.com/3BjSeaz.png)


### script.html
```html
<!doctype html>
<html>
    <body>

        <!--
            Ici, nous pouvons ajouter des éléments "dynamiques" et stylisés au formulaire. Par exemple, un fetch sur une API pour faire apparaitre une image ou même faire de l'autocompletion instantanément.

            Tips : Il est possible de créer un fichier PHP et de fetch sur celui-ci avec votre script JS.
            Ce qui veut dire que vous pouvez créer des modules plus complexes avec une architecture plus avancée.
            
            Tips2 : Vous pouvez utiliser Jquery (pas besoin d'importer la lib)
        -->

        <style>
            #imgExample{
                border: solid 5px red;
                margin-left: 20px;
            }
        </style>
        
        <script>
            /* Voir resultat sur la configuration du module*/

            console.log("Je suis dans l'espace de configuration/édition de mon module")

            fetch("https://api.unsplash.com/search/photos?query=cutecat&per_page=1&client_id=gK52De2Tm_dL5o1IXKa9FROBAJ-LIYqR41xBdlg3X2k")
            .then(response => response.json())
            .then(data => {
                let img = new Image()
                img.src = data.results[0].urls.thumb
                img.id = "imgExample"
                // module-html est l'endroit dans lequel vous pouvez ajouter vos éléments comme ci-dessous
                document.querySelector('#module-html').appendChild(img)
            })
                
        </script>

    </body>
</html>
```
#### Resultat : 

![Module Config Interface Scripting](https://i.imgur.com/ZblyByF.png)

### plugin.php

```php
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
```
#### Résultat des var_dump :

##### $data (Résultat de notre formulaire de configuration) :

```
(array) [2 elements]
    name: (string) "Titre"
    radioForm: (string) "one"
```

##### $config (informations sur le scan et l'objet qui a été scanné) : 

```
(array) [13 elements]
    id_plugin: (string) "1"
    id_record: (string) "1"
    id_product: (string) "1"
    timestamp: (string) "1643757168"
    hash: (string) "hash"
    path: (string) "plugin.php"

    product: (array) [2 elements]
        record: (array) [3 elements]
            id: (string) "1"
            technology: (string) "qrcode"
            token_encryption: (string) "static"
        reference: (string) "Carte démonstration"
    application: (array) [5 elements]
        id: (string) "3930"
        name: (string) "Dev"
        color: (string) "green"
        background_color: (string) "light"
        url_image: (null) NULL
    language: (string) "fr"
        browser: (array) [15 elements]
        browser_name_regex: (string) "~^mozilla/5\.0 \(.*windows nt 10\.0.*\).*applewebkit.*\(.*khtml.*like.*gecko.*\).*chrome/.* safari/.*$~"
        browser_name_pattern: (string) "Mozilla/5.0 (*Windows NT 10.0*)*applewebkit*(*khtml*like*gecko*)*Chrome/* Safari/*"
        parent: (string) "Chrome Generic"
        platform: (string) "Win10"
        comment: (string) "Chrome Generic"
        browser: (string) "Chrome"
        browser_maker: (string) "Google Inc"
        device_type: (string) "Desktop"
        device_pointing_method: (string) "mouse"
        version: (string) "0.0"
        majorver: (string) "0"
        minorver: (string) "0"
        ismobiledevice: (string) ""
        istablet: (string) ""
        crawler: (string) ""

    country_code: (string) "FR"

    share_link: (array) [2 elements]
        id: (string) "1"
        key: (string) "key"

    url_play: (string) "https://play.senzu.app/"
```

### Publication du module :

#### Récupérer mon token :
1. Pour obtenir mon token, vous devez vous rendre sur [notre plateforme pour développeur](https://developers.senzu.app/).

2. Connectez vous avec l'inspecteur sur l'onglet network :

![Inspector network login developpers](https://i.imgur.com/E7w02Qy.png)

3. Copiez votre token grâce à cette requête

#### Récupérer mon ID de version :

1. Sur l'espace développeur, créez un plugin et sa version

2. Ensuite, sur la liste de vos plugins :
    1. cliquez sur le bouton "Modify" de votre plugin
    2. cliquez sur "Versions" --> Versions List --> Edit
    3. Dans l'url, récupérez votre "id_version" :
      ``https://developers.senzu.app/plugins/{{id_plugin}}/versions/{{id_version}}``
    


#### Route : 

Url : ``https://api.senzu.app/files?token={{votreToken}}``

Méthode : ``POST``

Body : 
 
```
source : Module.zip
id_version : votreIdDeVersion
```
