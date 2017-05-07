<section class="widget widget-members widget-members-links">
  <ul>
    {option:!widgetMembersLinksIsLoggedIn}
    <li>
      <a href="{$var|geturlforblock:'Members':'Registration'}" title="{$lblMembersRegister|ucfirst}">
        <span class="glyphicon glyphicon-briefcase"></span>&nbsp;
        {$lblMembersRegister|ucfirst}
      </a>
    </li>
    <li>
      <a href="{$var|geturlforblock:'Profiles':'Login'}" title="{$lblMembersLogin|ucfirst}">
        <span class="glyphicon glyphicon-log-in"></span>&nbsp;
        {$lblMembersLogin|ucfirst}
      </a>
    </li>
    {/option:!widgetMembersLinksIsLoggedIn}
    {option:widgetMembersLinksIsLoggedIn}
    <li>
      <a href="{$var|geturlforblock:'Members':'Dashboard'}" title="{$lblMembersProfile|ucfirst}">
        <span class="glyphicon glyphicon-dashboard"></span>
        {$lblMembersDashboard|ucfirst}
      </a>
    </li>
    <li>
      <a href="{$var|geturlforblock:'Profiles':'Logout'}" title="{$lblProfilesLogout|ucfirst}">
        <span class="glyphicon glyphicon-log-out"></span>&nbsp;{$lblProfilesLogout|ucfirst}
      </a>
    </li>
    {/option:widgetMembersLinksIsLoggedIn}
  </ul>
</section>
