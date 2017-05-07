<h1>{$member.display_name}</h1>
<section id="members-detail" class="module module-members layout-members">
  <div class="row">
    <div class="col-md-2">
      <img src="{$FRONTEND_FILES_URL}/Members/avatars/256x256/{$member.avatar}" class="img-thumbnail" alt="{$member.display_name}" />
    </div>
    <div class="col-md-10">
      <table class="table">
        <tr>
          <th>{$lblMembersEmail}</th>
          <th>
            <a href="mailto:{$member.email}" title="{$member.email}">{$member.email}</a>
          </th>
        </tr>
        {* option:member.groups}
        <tr>
          <th>{$lblMembersGroups}</th>
          <td>
            {iteration:member.groups}
            {$member.groups.locale.title}{option:!member.groups.last}, {/option:!member.groups.last}
            {/iteration:member.groups}
          </td>
        </tr>
        {/option:member.groups *}
      </table>
      <h2>{$lblMembersIntroduction|ucfirst}</h2>
      <div>
        {$member.locale.introduction}
      </div>
    </div>
  </div>
</section>
