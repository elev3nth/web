<label class="switch">
  <input type="checkbox"
    id="{{ citem.varf }}"
    name="{{ citem.varf }}"
    {{ args.page == 'list' or args.page == 'view' ? 'disabled' : false }}
    {{ content.data[citem.flds] == 1 ? 'checked' : false }}
  />
  <span class="slider round"></span>
</label>
