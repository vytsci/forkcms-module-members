{option:!widgetMembersPromotionsIsLoggedIn}
<section class="widget widget-members widget-members-promotion">
  <div class="container">
    <div class="col-md-12">
      <div class="call-to-action">
        <div class="call-to-action-inner">
          <div class="call-to-action-title">
            <div class="logo-shape"></div>
            <h2 class="h1">{$lblMembersPromotion|ucfirst}</h2>
            <h3 class="h2">{$msgMembersPromotion|ucfirst}</h3>
          </div>
          <a href="{$var|geturlforblock:'Members':'Registration'}" class="btn btn-primary" title="{$lblMembersPromotionRegistration|ucfirst}">
            {$lblMembersPromotionRegistration|ucfirst}
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
{/option:!widgetMembersPromotionsIsLoggedIn}
