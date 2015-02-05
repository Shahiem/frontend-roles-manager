###Manage Groups & Permissions######
Go to the Users page in the backend and look for the section named Groups or Permissions.

###Check if a user has a role######

    {% if hasRole('Admin') %}
     Admin Power!
    {% endif %}

###Check if a user has a permission######

    {% if can('manage_post') %}
    I can manage this.
    {% endif %}

###Like this plugin?######
If you like this plugin or if you use some of my plugins, you can help me to make new plugins and provide plugins support and further development. Make donation with PayPal or give this plugin a Like. :)
