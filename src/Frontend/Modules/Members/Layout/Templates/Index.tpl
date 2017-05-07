{option:members}
<section id="members-index" class="module module-members layout-members">
  <div class="row">
    <div class="col-md-12">
      <div class="layout-members-list">
        {iteration:members}
        <a href="{$members.member_url}" class="layout-members-item" title="{$members.display_name}">
          <div class="row">
            <div class="col-md-2">
              <img src="{$FRONTEND_FILES_URL}/Members/avatars/256x256/{$members.avatar}" class="img-thumbnail" alt="{$members.display_name}" />
            </div>
            <div class="col-md-10">
              <h2>{$members.display_name}</h2>
              <div>{$members.email}</div>
              {option:members.groups}
              <div>
                {iteration:members.groups}
                {$members.groups.title}{option:!members.groups.last}, {/option:!members.groups.last}
                {/iteration:members.groups}
              </div>
              {/option:members.groups}
            </div>
          </div>
        </a>
        {/iteration:members}
      </div>
    </div>
  </div>
</section>
{/option:members}
