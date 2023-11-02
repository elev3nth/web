
{% if args.page is not defined or
      args.page == list or
      args.page == view
%}
  {% if citem.link|default(false) == true and args.page != view %}
    <a href="{{ host~'/'~admin~'/'~links|join('/')~'/'~view~'/'~
    ditem[content.table.pfx~content.table.key] }}" class="underline">
      {{ ditem[citem.flds] }}
    </a>
  {% else %}
    {% if args.page == view %}
      {{ content.data[citem.flds] }}
    {% else %}
      {{ ditem[citem.flds] }}
    {% endif %}
  {% endif %}
{% else %}
  <input type="text"
    id="{{ citem.varf }}"
    name="{{ citem.varf }}"
    value="{{ args.page != create ? content.data[citem.flds] : false }}"
    class="w-full h-[2em] p-2 border rounded-sm border-gray-400" />
{% endif %}
