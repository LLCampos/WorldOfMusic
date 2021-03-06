define({ "api": [
  {
    "type": "post",
    "url": "/artist/:name_of_artist",
    "title": "Submit a new Artist addition request",
    "name": "AddArtist",
    "group": "Artist",
    "version": "0.0.1",
    "description": "<p>Submits a new Artist to approval by other users. In practise, this method creates a new Pending Addition. The Artist will be added if it receive 5 positive votes. If it receives 5 negatives votes it will not.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name_of_artist",
            "description": "<p>Name of musical Artist to add</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "country",
            "description": "<p>ISO 3166-1 alpha-2 code of the Country</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\"message\":\"Request submitted.\"}",
          "type": "json"
        },
        {
          "title": "Success Response (XML):",
          "content": "<message>Request submitted.</message>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/artist.php",
    "groupTitle": "Artist",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/artist/:name_of_artist"
      }
    ]
  },
  {
    "type": "delete",
    "url": "/artist/:name_of_artist",
    "title": "Submit an Artist deletion request",
    "name": "DeleteArtist",
    "group": "Artist",
    "version": "0.0.1",
    "description": "<p>Submits a deletion of Artist to approval by other users. In practise, this method creates a new Pending Deletion. The Artist will be deleted if it receive 5 positive votes. If it receives 5 negatives votes it will not.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name_of_artist",
            "description": "<p>Name of musical Artist to delete</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\"message\":\"Request submitted.\"}",
          "type": "json"
        },
        {
          "title": "Success Response (XML):",
          "content": "<message>Request submitted.</message>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/artist.php",
    "groupTitle": "Artist",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/artist/:name_of_artist"
      }
    ]
  },
  {
    "type": "put",
    "url": "/artist/:name_of_artist",
    "title": "Edit information about Artist",
    "name": "EditArtist",
    "group": "Artist",
    "version": "0.0.1",
    "description": "<p>Submits a new edition to approval by other users. In practise, this method creates a new Pending Edition. The edition will be accepted if it receive 5 positive votes. If it receives 5 negatives votes it will not.</p> <p>You can only request edition of one parameter.</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name_of_artist",
            "description": "<p>Name of Artist of which information you want to edit</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "style",
            "description": "<p>New music genre played by Artist</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "country",
            "description": "<p>New country name or ISO 3166-1 alpha-2 country code of Artist</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "facebook_url",
            "description": "<p>New artist Facebook page URL</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Example of changing the style of Metallica to Thrash Metal:",
        "content": "PUT /artist/Metallica?style=Thrash Metal",
        "type": "json"
      }
    ],
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/artist.php",
    "groupTitle": "Artist",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/artist/:name_of_artist"
      }
    ]
  },
  {
    "type": "get",
    "url": "/artist/:artist_name",
    "title": "Get information about Artist",
    "name": "GetArtistInformation",
    "group": "Artist",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "artist_name",
            "description": "<p>Name of Artist</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "style",
            "description": "<p>Music genre played by Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "country",
            "description": "<p>Country of Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "picture_url",
            "description": "<p>Image of Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "lastfm_url",
            "description": "<p>URL to Artist Last.fm page</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "number_of_lastfm_listeners",
            "description": "<p>Number of Artist in Last.fm website</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "biography",
            "description": "<p>Short biography Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "music_video",
            "description": "<p>An ID of one Youtube music video from Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "facebook_id",
            "description": "<p>Artist Facebook page ID</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "number_of_facebook_likes",
            "description": "<p>Number of likes at Artist Facebook Page</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "twitter_url",
            "description": "<p>URL to Artist Twitter Page</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "number_of_twitter_followers",
            "description": "<p>Number of followers of Artist Twitter Page</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "musicbrainz_id",
            "description": "<p>Artist Musicbrainz ID</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n  \"name\": \"Capicua\",\n  \"style\": \"hip-hop\",\n  \"country\": \"Portugal\",\n  \"picture_url\": \"http://img2-ak.lst.fm/i/u/174s/4a7fbbf4749645cda4025c1deb829273.png\",\n  \"lastfm_url\": \"http://www.last.fm/music/Capicua\",\n  \"number_of_lastfm_listeners\": ​3880,\n  \"biography\": \"About CAPICUA\\n\\nSe chegaste até aqui (...)\",\n  \"music_video\": null,\n  \"facebook_id\": \"272101826169708\",\n  \"number_of_facebook_likes\": ​80212,\n  \"twitter_url\": null,\n  \"number_of_twitter_followers\": null,\n  \"musicbrainz_id\": \"451107dc-7d40-4bd4-86e6-f76e566ff17b\"\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<?xml version=\"1.0\"?>\n<artist>\n    <name>Capicua</name>\n    <style>hip-hop</style>\n    <country>Portugal</country>\n    <picture_url>http://img2-ak.lst.fm/i/u/174s/4a7fbbf4749645cda4025c1deb829273.png</picture_url>\n    <lastfm_url>http://www.last.fm/music/Capicua</lastfm_url>\n    <number_of_lastfm_listeners>3880</number_of_lastfm_listeners>\n    <biography>About CAPICUA\\n\\nSe chegaste até aqui (...)</biography>\n    <music_video/>\n    <facebook_id>272101826169708</facebook_id>\n    <number_of_facebook_likes>80212</number_of_facebook_likes>\n    <twitter_url/>\n    <number_of_twitter_followers/>\n    <musicbrainz_id>451107dc-7d40-4bd4-86e6-f76e566ff17b</musicbrainz_id>\n</artist>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/artist.php",
    "groupTitle": "Artist",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/artist/:artist_name"
      }
    ]
  },
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/doc/main.js",
    "group": "C__Users_Luis_Google_Drive_Mestrado_2_Semeste_AW_Projecto_WorldOfMusic_public_html_webservices_doc_main_js",
    "groupTitle": "C__Users_Luis_Google_Drive_Mestrado_2_Semeste_AW_Projecto_WorldOfMusic_public_html_webservices_doc_main_js",
    "name": ""
  },
  {
    "type": "get",
    "url": "/country",
    "title": "Get list of Countries",
    "name": "GetCountries",
    "group": "Country",
    "version": "0.0.1",
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the Country</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>Path to the Country resource</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n    \"countries\": [\n  {\n    \"code\": \"af\"\n    \"name\": \"Afghanistan\",\n    \"url\": \"/country/Afghanistan\"\n   },\n   {\n    \"code\": \"ax\"\n    \"name\": \"Åland Islands\",\n    \"url\": \"/country/Åland Islands\"\n    },\n(...)\n   ]\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<countries>\n    <country>\n        <code>af</code>\n        <name>Afghanistan</name>\n        <url>/country/Afghanistan</url>\n    </country>\n    <country>\n        <code>ax</code>\n        <name>Åland Islands</name>\n        <url>/country/Åland Islands</url>\n    </country>\n    (...)\n</countries>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/country.php",
    "groupTitle": "Country",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/country"
      }
    ]
  },
  {
    "type": "get",
    "url": "/country/:country_code",
    "title": "Get information about Country",
    "name": "GetCountry",
    "group": "Country",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "country_code",
            "description": "<p>ISO 3166-1 alpha-2 code for the country</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the Country</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "name_alpha2",
            "description": "<p>ISO 3166-1 alpha-2 code for the country</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "flag_img_url",
            "description": "<p>URL to an image of the country flag</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "capital",
            "description": "<p>Name of the country's capital</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "population",
            "description": "<p>Number of people in the country</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "region",
            "description": "<p>Name of the country's continent</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "subregion",
            "description": "<p>Name of the subregion of the country</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "description_of_music",
            "description": "<p>Description of the music in the country</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n    \"name\": \"China\",\n    \"name_alpha2\": \"cn\",\n    \"flag_img_url\": \"http://www.geonames.org/flags/x/cn.gif\",\n    \"capital\": \"Beijing\",\n    \"population\": ​1371590000,\n    \"region\": \"Asia\",\n    \"subregion\": \"Eastern Asia\",\n    \"description_of_music\": \"Music of China refers to the music of the (...)\"\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<country>\n    <name>China</name>\n    <name_alpha2>cn</name_alpha2>\n    <flag_img_url>http://www.geonames.org/flags/x/cn.gif</flag_img_url>\n    <capital>Beijing</capital>\n    <population>1371590000</population>\n    <region>Asia</region>\n    <subregion>Eastern Asia</subregion>\n    <description_of_music>Music of China refers to the music of (...) </description_of_music>\n</country>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/country.php",
    "groupTitle": "Country",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/country/:country_code"
      }
    ]
  },
  {
    "type": "get",
    "url": "/country/:name_of_country/artists",
    "title": "Get list of Artists of Country",
    "name": "GetCountryArtists",
    "group": "Country",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name_of_country",
            "description": "<p>Name of the Country</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "defaultValue": "20",
            "description": "<p>Number of Artists/Group to be returned</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"likes\"",
              "\"random\"",
              "\"lastfm\""
            ],
            "optional": true,
            "field": "order",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "defaultValue": "1",
            "description": "<p>Page to be returned</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Name of the Artist</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n  \"artist\": [\n      {\n          \"name\": \"Jorge Palma\",\n          \"style\": \"singer-songwriter\",\n          \"country\": \"Portugal\",\n          \"picture_url\": \"http://img2-ak.lst.fm/i/u/174s/3e48b70219b24a03a7e7bc8cee06de9f.png\",\n          \"lastfm_url\": \"http://www.last.fm/music/Jorge+Palma\",\n          \"number_of_lastfm_listeners\": 23591,\n          \"music_video\": \"NgUPRDIwu1U\",\n          \"facebook_id\": \"288425643789\",\n          \"number_of_facebook_likes\": 239059,\n          \"twitter_url\": null,\n          \"number_of_twitter_followers\": null,\n          \"musicbrainz_id\": \"386c9f8f-31d8-4815-b4c5-7c875f96c2b0\"\n      },\n      {\n\n          \"name\": \"Ena Pá 2000\",\n          \"style\": \"rock\",\n          \"country\": \"Portugal\",\n          \"picture_url\": \"http://img2-ak.lst.fm/i/u/174s/dd106ce8a47a45e486e6ffe890ae3eb8.png\",\n          \"lastfm_url\": \"http://www.last.fm/music/+noredirect/Ena+P%C3%A1+2000\",\n          \"number_of_lastfm_listeners\": 4971,\n          \"music_video\": \"CR6K5iaAHho\",\n          \"facebook_id\": \"137403672998884\",\n          \"number_of_facebook_likes\": 4435,\n          \"twitter_url\": null,\n          \"number_of_twitter_followers\": null,\n          \"musicbrainz_id\": \"4dc776c2-a16e-46d8-8a81-8c63804f373f\"\n      }\n  ]\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<artists>\n   <artist>\n       <name>Jorge Palma</name>\n       <style>singer-songwriter</style>\n       <country>Portugal</country>\n       <picture_url>http://img2-ak.lst.fm/i/u/174s/3e48b70219b24a03a7e7bc8cee06de9f.png</picture_url>\n       <lastfm_url>http://www.last.fm/music/Jorge+Palma</lastfm_url>\n       <number_of_lastfm_listeners>23591</number_of_lastfm_listeners>\n       <music_video>NgUPRDIwu1U</music_video>\n       <facebook_id>288425643789</facebook_id>\n       <number_of_facebook_likes>239059</number_of_facebook_likes>\n       <twitter_url/>\n       <number_of_twitter_followers/>\n       <musicbrainz_id>386c9f8f-31d8-4815-b4c5-7c875f96c2b0</musicbrainz_id>\n   </artist>\n   <artist>\n       <name>Ena P&#xE1; 2000</name>\n       <style>rock</style>\n       <country>Portugal</country>\n       <picture_url>http://img2-ak.lst.fm/i/u/174s/dd106ce8a47a45e486e6ffe890ae3eb8.png</picture_url>\n       <lastfm_url>http://www.last.fm/music/+noredirect/Ena+P%C3%A1+2000</lastfm_url>\n       <number_of_lastfm_listeners>4971</number_of_lastfm_listeners>\n       <music_video>CR6K5iaAHho</music_video>\n       <facebook_id>137403672998884</facebook_id>\n       <number_of_facebook_likes>4435</number_of_facebook_likes>\n       <twitter_url/>\n       <number_of_twitter_followers/>\n       <musicbrainz_id>4dc776c2-a16e-46d8-8a81-8c63804f373f</musicbrainz_id>\n   </artist>\n</artists>",
          "type": "xml"
        }
      ]
    },
    "examples": [
      {
        "title": "Getting a random list of 5 Portuguese Artists:",
        "content": "GET /country/Portugal/artists?limit=5&order=random",
        "type": "json"
      }
    ],
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/country.php",
    "groupTitle": "Country",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/country/:name_of_country/artists"
      }
    ]
  },
  {
    "type": "get",
    "url": "/pending_addition/:pending_addition_id",
    "title": "Get information about Pending Addition",
    "name": "GetPendingAddition",
    "group": "Pending_Addition",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pending_addition_id",
            "description": "<p>ID of the Pending Addition</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID of the Pending Addition</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "artist_name",
            "description": "<p>Name of the Artist being added</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "positive_votes",
            "description": "<p>Number of votes in favor of adding the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "negative_votes",
            "description": "<p>Number of votes against adding the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "added_by",
            "description": "<p>ID of the user who added the Artist</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n    \"id\": ​6,\n    \"artist_name\": \"Megadeth\",\n    \"positive_votes\": ​0,\n    \"negative_votes\": ​0,\n    \"added_by\": \"10201440175723123\",\n    \"creation_time\": \"2016-05-02 20:43:28\"\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<pending_addition>\n    <id>6</id>\n    <artist_name>Megadeth</artist_name>\n    <positive_votes>0</positive_votes>\n    <negative_votes>0</negative_votes>\n    <added_by>10201440175723123</added_by>\n    <creation_time>2016-05-02 20:43:28</creation_time>\n</pending_addition>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/pending_addition.php",
    "groupTitle": "Pending_Addition",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/pending_addition/:pending_addition_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/pending_addition",
    "title": "Get list of Pending Additions",
    "name": "GetPendingAdditions",
    "group": "Pending_Addition",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "defaultValue": "20",
            "description": "<p>Number of Pending Additions to be returned</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"date_asc\"",
              "\"date_desc\"",
              "\"random\""
            ],
            "optional": true,
            "field": "order",
            "defaultValue": "date_asc",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "defaultValue": "1",
            "description": "<p>Page to be returned</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID of the Pending Addition</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "artist_name",
            "description": "<p>Name of the Artist being added</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "positive_votes",
            "description": "<p>Number of votes in favor of adding the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "negative_votes",
            "description": "<p>Number of votes against adding the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "added_by",
            "description": "<p>ID of the user who added the Artist</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n    \"pending_additions\": [\n        {\n            \"id\": ​6,\n            \"artist_name\": \"Megadeth\",\n            \"positive_votes\": ​0,\n            \"negative_votes\": ​0,\n            \"added_by\": \"10201440175723123\",\n            \"creation_time\": \"2016-05-02 20:43:28\"\n        },\n        {\n            \"id\": ​8,\n            \"artist_name\": \"Slayer\",\n            \"positive_votes\": ​0,\n            \"negative_votes\": ​0,\n            \"added_by\": \"10201440175723123\",\n            \"creation_time\": \"2016-05-03 14:27:07\"\n        },\n        (...)\n    ]\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<pending_additions>\n    <pending_addition>\n        <id>6</id>\n        <artist_name>Megadeth</artist_name>\n        <positive_votes>0</positive_votes>\n        <negative_votes>0</negative_votes>\n        <added_by>10201440175723123</added_by>\n        <creation_time>2016-05-02 20:43:28</creation_time>\n    </pending_addition>\n    <pending_addition>\n        <id>8</id>\n        <artist_name>Slayer</artist_name>\n        <positive_votes>0</positive_votes>\n        <negative_votes>0</negative_votes>\n        <added_by>10201440175723123</added_by>\n        <creation_time>2016-05-03 14:27:07</creation_time>\n    </pending_addition>\n    (...)\n</pending_additions>",
          "type": "xml"
        }
      ]
    },
    "examples": [
      {
        "title": "Getting the more recent Pedding Addition:",
        "content": "GET /pending_addition/limit=1&order=data_desc",
        "type": "json"
      }
    ],
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/pending_addition.php",
    "groupTitle": "Pending_Addition",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/pending_addition"
      }
    ]
  },
  {
    "type": "post",
    "url": "/pending_addition/:pending_addition_id/:type_of_vote",
    "title": "Vote on one Pending Addition",
    "name": "VotePendingAddition",
    "group": "Pending_Addition",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pending_addition_id",
            "description": "<p>ID of the Pending Addition</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"positive_vote\"",
              "\"negative_vote\""
            ],
            "optional": false,
            "field": "type_of_vote",
            "description": "<p>Type of vote you want to add</p>"
          }
        ]
      }
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/pending_addition.php",
    "groupTitle": "Pending_Addition",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/pending_addition/:pending_addition_id/:type_of_vote"
      }
    ]
  },
  {
    "type": "get",
    "url": "/pending_deletion/:pending_deletion_id",
    "title": "Get info about Pending Deletion",
    "name": "GetPendingDeletion",
    "group": "Pending_Deletion",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pending_deletion_id",
            "description": "<p>ID of the Pending Deletion</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID of the Pending Deletion</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "artist_name",
            "description": "<p>Name of the Artist being deleted</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "positive_votes",
            "description": "<p>Number of votes in favor of deletion the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "negative_votes",
            "description": "<p>Number of votes against deletion the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "added_by",
            "description": "<p>ID of the user who asked for the deletion of the Artist</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n   \"id\": ​9,\n   \"artist_name\": \"Gabriel Fliflet\",\n   \"positive_votes\": ​0,\n   \"negative_votes\": ​1,\n   \"added_by\": \"102503863489106\",\n   \"creation_time\": \"2016-05-03 14:29:34\"\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<pending_deletion>\n    <id>​9</id>\n    <artist_name>Gabriel Fliflet</artist_name>\n    <positive_votes>0</positive_votes>\n    <negative_votes>1</negative_votes>\n    <added_by>10201440175723123</added_by>\n    <creation_time>2016-05-03 14:29:34</creation_time>\n</pending_deletion>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/pending_deletion.php",
    "groupTitle": "Pending_Deletion",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/pending_deletion/:pending_deletion_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/pending_deletion",
    "title": "Get list of Pending Deletion",
    "name": "GetPendingDeletions",
    "group": "Pending_Deletion",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "defaultValue": "20",
            "description": "<p>Number of Pending Deletions to be returned</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"date_asc\"",
              "\"date_desc\"",
              "\"random\""
            ],
            "optional": true,
            "field": "order",
            "defaultValue": "date_asc",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "defaultValue": "1",
            "description": "<p>Page to be returned</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID of the Pending Deletion</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "artist_name",
            "description": "<p>Name of the Artist being deleted</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "positive_votes",
            "description": "<p>Number of votes in favor of deletion the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "negative_votes",
            "description": "<p>Number of votes against deleting the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "added_by",
            "description": "<p>ID of the user who deleted the Artist</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n    \"pending_deletions\": [\n        {\n            id\": ​8,\n            \"artist_name\": \"Brazen Abbot\",\n            \"positive_votes\": ​1,\n            \"negative_votes\": ​0,\n            \"added_by\": \"102503863489106\",\n            \"creation_time\": \"2016-05-03 14:29:34\"\n        },\n        {\n            \"id\": ​9,\n            \"artist_name\": \"Gabriel Fliflet\",\n            \"positive_votes\": ​0,\n            \"negative_votes\": ​1,\n            \"added_by\": \"102503863489106\",\n            \"creation_time\": \"2016-05-02 12:59:18\"\n        },\n        (...)\n    ]\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<pending_deletions>\n    <pending_deletion>\n        <id>8</id>\n        <artist_name>Brazen Abbot</artist_name>\n        <positive_votes>1</positive_votes>\n        <negative_votes>0</negative_votes>\n        <added_by>10201440175723123</added_by>\n        <creation_time>2016-05-03 14:29:34</creation_time>\n    </pending_deletion>\n    <pending_deletion>\n        <id>​9</id>\n        <artist_name>Gabriel Fliflet</artist_name>\n        <positive_votes>0</positive_votes>\n        <negative_votes>1</negative_votes>\n        <added_by>10201440175723123</added_by>\n        <creation_time>2016-05-02 12:59:18</creation_time>\n    </pending_deletion>\n    (...)\n</pending_deletions>",
          "type": "xml"
        }
      ]
    },
    "examples": [
      {
        "title": "Getting the more recent Pedding Deletion:",
        "content": "GET /pending_deletion/limit=1&order=data_desc",
        "type": "json"
      }
    ],
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/pending_deletion.php",
    "groupTitle": "Pending_Deletion",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/pending_deletion"
      }
    ]
  },
  {
    "type": "post",
    "url": "/pending_deletion/:pending_deletion_id/:type_of_vote",
    "title": "Vote on one Pending Deletion",
    "name": "VotePendingDeletion",
    "group": "Pending_Deletion",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pending_deletion_id",
            "description": "<p>ID of the Pending Deletion</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"positive_vote\"",
              "\"negative_vote\""
            ],
            "optional": false,
            "field": "type_of_vote",
            "description": "<p>Type of vote you want to add</p>"
          }
        ]
      }
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/pending_deletion.php",
    "groupTitle": "Pending_Deletion",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/pending_deletion/:pending_deletion_id/:type_of_vote"
      }
    ]
  },
  {
    "type": "get",
    "url": "/pending_edition/:pending_edition_id",
    "title": "Get information about Pending Edition",
    "name": "GetPendingEdition",
    "group": "Pending_Edition",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pending_edition_id",
            "description": "<p>ID of the Pending Edition</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID of the Pending Edition</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "artist_name",
            "description": "<p>Name of the Artist being edited</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "attribute_changing",
            "description": "<p>Attribute of which an edition was requested</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "old_value",
            "description": "<p>The value of the attribute that is being replaced</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "new_value",
            "description": "<p>The new proposal for the value of the attribute</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "positive_votes",
            "description": "<p>Number of votes in favor of adding the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "negative_votes",
            "description": "<p>Number of votes against adding the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "added_by",
            "description": "<p>ID of the user who edited the Artist</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n    \"id\": ​7,\n    \"artist_name\": \"Megadeth\",\n    \"attribute_changing\": \"style\",\n    \"old_value\": \"thrash metal\",\n    \"new_value\": \"heavy metal\",\n    \"positive_votes\": ​0,\n    \"negative_votes\": ​0,\n    \"added_by\": \"42\",\n    \"creation_time\": \"2016-05-16 16:39:16\"\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<pending_edition>\n    <id>7</id>\n    <artist_name>Megadeth</artist_name>\n    <attribute_changing>style</attribute_changing>\n    <old_value>thrash metal</old_value>\n    <new_value>heavy metal</new_value>\n    <positive_votes>0</positive_votes>\n    <negative_votes>0</negative_votes>\n    <added_by>42</added_by>\n    <creation_time>2016-05-16 16:39:16</creation_time>\n</pending_edition>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/pending_edition.php",
    "groupTitle": "Pending_Edition",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/pending_edition/:pending_edition_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/pending_edition",
    "title": "Get list of Pending Editions",
    "name": "GetPendingEditions",
    "group": "Pending_Edition",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "defaultValue": "20",
            "description": "<p>Number of Pending Editions to be returned</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"date_asc\"",
              "\"date_desc\"",
              "\"random\""
            ],
            "optional": true,
            "field": "order",
            "defaultValue": "date_asc",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "page",
            "defaultValue": "1",
            "description": "<p>Page to be returned</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>ID of the Pending Edition</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "artist_name",
            "description": "<p>Name of the Artist being edited</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "attribute_changing",
            "description": "<p>Attribute of which an edition was requested</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "positive_votes",
            "description": "<p>Number of votes in favor of adding the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "negative_votes",
            "description": "<p>Number of votes against adding the Artist</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "added_by",
            "description": "<p>ID of the user who edited the Artist</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n    \"pending_editions\": [\n        {\n          \"id\": ​7,\n          \"artist_name\": \"Megadeth\",\n          \"attribute_changing\": \"style\",\n          \"positive_votes\": ​0,\n          \"negative_votes\": ​0,\n          \"added_by\": \"42\",\n          \"creation_time\": \"2016-05-16 16:39:16\"\n        },\n        {\n          \"id\": ​8,\n          \"artist_name\": \"Elena Roger\",\n          \"attribute_changing\": \"facebook_url\",\n          \"positive_votes\": ​0,\n          \"negative_votes\": ​0,\n          \"added_by\": \"42\",\n          \"creation_time\": \"2016-05-16 16:48:10\"\n        },\n        (...)\n    ]\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<?xml version=\"1.0\"?>\n<pending_editions>\n    <pending_edition>\n        <id>7</id>\n        <artist_name>Megadeth</artist_name>\n        <attribute_changing>style</attribute_changing>\n        <positive_votes>0</positive_votes>\n        <negative_votes>0</negative_votes>\n        <added_by>42</added_by>\n        <creation_time>2016-05-16 16:39:16</creation_time>\n    </pending_edition>\n    <pending_edition>\n        <id>8</id>\n        <artist_name>Elena Roger</artist_name>\n        <attribute_changing>facebook_url</attribute_changing>\n        <positive_votes>0</positive_votes>\n        <negative_votes>0</negative_votes>\n        <added_by>42</added_by>\n        <creation_time>2016-05-16 16:48:10</creation_time>\n    </pending_edition>\n</pending_editions>",
          "type": "xml"
        }
      ]
    },
    "examples": [
      {
        "title": "Getting the more recent Pedding Edition:",
        "content": "GET /pending_edition/limit=1&order=data_desc",
        "type": "json"
      }
    ],
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/pending_edition.php",
    "groupTitle": "Pending_Edition",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/pending_edition"
      }
    ]
  },
  {
    "type": "post",
    "url": "/pending_edition/:pending_edition_id/:type_of_vote",
    "title": "Vote on one Pending Edition",
    "name": "VotePendingEdition",
    "group": "Pending_Edition",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "pending_edition_id",
            "description": "<p>ID of the Pending Edition</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "\"positive_vote\"",
              "\"negative_vote\""
            ],
            "optional": false,
            "field": "type_of_vote",
            "description": "<p>Type of vote you want to add</p>"
          }
        ]
      }
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/pending_edition.php",
    "groupTitle": "Pending_Edition",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/pending_edition/:pending_edition_id/:type_of_vote"
      }
    ]
  },
  {
    "type": "get",
    "url": "/user/:id",
    "title": "Get information about User",
    "name": "GetUser",
    "group": "User",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>User ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "first_name",
            "description": "<p>User's first name</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "last_name",
            "description": "<p>User's last name</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "gender",
            "description": "<p>User's gender</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "locale",
            "description": "<p>User country</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "age_range",
            "description": "<ul> <li></li> </ul>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "timezone",
            "description": "<p>The User's current timezone offset from UTC</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "pending_",
            "description": "<p>Number of User pending submissions</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "successful_",
            "description": "<p>Number of User successful submissions</p>"
          },
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "unsuccessful_",
            "description": "<p>Number of User unsuccessful submissions</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n    \"id\": \"10201440175723123\",\n    \"first_name\": \"Luis\",\n    \"last_name\": \"Campos\",\n    \"gender\": \"male\",\n    \"locale\": \"pt_PT\",\n    \"age_range\": ​21,\n    \"timezone\": ​1,\n    \"pending_additions\": ​0,\n    \"successful_additions\": ​1,\n    \"unsuccessful_additions\": ​2,\n    \"pending_deletions\": ​0,\n    \"successful_deletions\": ​0,\n    \"unsuccessful_deletions\": ​0,\n    \"pending_editions\": ​0,\n    \"successful_editions\": ​0,\n    \"unsuccessful_editions\": ​0\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "<user>\n    <id>10201440175723123</id>\n    <first_name>Luis</first_name>\n    <last_name>Campos</last_name>\n    <gender>male</gender>\n    <locale>pt_PT</locale>\n    <age_range>21</age_range>\n    <timezone>1</timezone>\n    <pending_additions>0</pending_additions>\n    <successful_additions>1</successful_additions>\n    <unsuccessful_additions>2</unsuccessful_additions>\n    <pending_deletions>0</pending_deletions>\n    <successful_deletions>0</successful_deletions>\n    <unsuccessful_deletions>0</unsuccessful_deletions>\n    <pending_editions>0</pending_editions>\n    <successful_editions>0</successful_editions>\n    <unsuccessful_editions>0</unsuccessful_editions>\n</user>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/user.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/user/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/user/:id/votes",
    "title": "Get User votes",
    "name": "GetUserVotes",
    "group": "User",
    "version": "0.0.1",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>User ID</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200 OK": [
          {
            "group": "200 OK",
            "type": "Number",
            "optional": false,
            "field": "addition_id",
            "description": "<p>ID of the addition</p>"
          },
          {
            "group": "200 OK",
            "type": "String",
            "optional": false,
            "field": "type_of_votes",
            "description": "<p>If the vote was negative or positive. If null, it means that the user was the one who requested the addition/deletion/edition</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response (JSON):",
          "content": "{\n    \"addition_votes\": [\n        {\n            \"addition_id\": ​12,\n            \"type_of_vote\": \"negative\"\n        },\n        {\n            \"addition_id\": ​14,\n            \"type_of_vote\": \"positive\"\n        },\n        {\n            \"addition_id\": ​18,\n            \"type_of_vote\": null\n        }\n    ]\n    \"deletion_votes\": [\n         {\n             \"deletion_id\": ​8,\n             \"type_of_vote\": \"positive\"\n         },\n         {\n             \"deletion_id\": ​9,\n             \"type_of_vote\": \"negative\"\n         }\n   ]\n}",
          "type": "json"
        },
        {
          "title": "Success Response (XML)",
          "content": "\n<votes>\n    <addition_votes>\n        <addition_vote>\n            <addition_id>12</addition_id>\n            <type_of_vote>negative</type_of_vote>\n        </addition_vote>\n        <addition_vote>\n            <addition_id>14</addition_id>\n            <type_of_vote>positive</type_of_vote>\n        </addition_vote>\n        <addition_vote>\n            <addition_id>18</addition_id>\n            <type_of_vote/>\n        </addition_vote>\n        <addition_vote>\n            <addition_id>19</addition_id>\n            <type_of_vote/>\n        </addition_vote>\n        <addition_vote>\n            <addition_id>20</addition_id>\n            <type_of_vote>positive</type_of_vote>\n        </addition_vote>\n    </addition_votes>\n    <deletion_votes>\n        <deletion_vote>\n            <deletion_id>8</deletion_id>\n            <type_of_vote>positive</type_of_vote>\n        </deletion_vote>\n        <deletion_vote>\n            <deletion_id>9</deletion_id>\n            <type_of_vote>negative</type_of_vote>\n        </deletion_vote>\n    </deletion_votes>\n</votes>",
          "type": "xml"
        }
      ]
    },
    "filename": "Mestrado/2_Semeste/AW/Projecto/WorldOfMusic/public_html/webservices/user.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://appserver.di.fc.ul.pt/~aw008/webservices/user/:id/votes"
      }
    ]
  }
] });
