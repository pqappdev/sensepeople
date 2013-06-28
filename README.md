Sense people YiiFramework extension.
===========

Sense People [hakkında](http://people.sensekit.com/ "http://people.sensekit.com/")

Yii için sensepeople'a data göndermek için yazılmış extension.

Kurulum:
======

###protected/config/main.php
<pre>
<code>
return array(
    
    'components' => array(

     'sensePeople' => array(
            'class' => 'ext.sensepeople.components.SensePeople',
            'saveHistoryToDb' => TRUE,
            'accountName' => '',
            'campaignId' => '',
            'campaignToken' => '',
      ),
    ),
    
);
</code>
</pre>

###Eğer gönderilen data'yı bir tabloda tutmak isterseniz.

### protected/config/console.php
<pre><code>
return array(

    'commandMap' => array(

        'sensepeople' => array(
            'class' => 'application.extensions.sensepeople.commands.SensePeopleCommand',
        ),

    ),

);
</code></pre>

Console'a extension'ı ekledikten sonra kurulum için gerekli cli komutu:<br><br>
<code>
$ protected/yiic sensepeople
</code>

##Data Gönderimi
<pre><code>
            $customer = array(
                     'facebook_id' => ,
                     'facebook_username' => ,
                     'facebook_name' => ,
                     'facebook_likes' => ,
                     'facebook_gender' => ,
                     'facebook_hometown' => ,
                     'facebook_education' => ,
                     'facebook_location' => ,
                     'facebook_birthday' => ,
                     'facebook_work' => ,
                     'facebook_relationship_status' => ,
                     'facebook_access_token' => ,
                     'gender' => ,
                     'facebook_phone' => ,
                     'email' => ,
                     'first_name' => ,
                     'last_name' => ,
                     'joined_at' =>,
                 );
            
            $sensePeopleResult = Yii::app()->sensePeople->push($customer);

</code></pre>