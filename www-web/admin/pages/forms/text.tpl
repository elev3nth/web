
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
  {% set fvalue = '' %}
  {% if crud_response.post[citem.varf] is defined %}
    {% set fvalue = crud_response.post[citem.varf] %}
  {% else %}
    {% if args.page != create %}
      {% set fvalue = content.data[citem.flds] %}
    {% endif %}
  {% endif %}
  <input type="text"
    id="{{ citem.varf }}"
    name="{{ citem.varf }}"
    value="{{ fvalue }}"
    class="w-full h-[2em] p-2 border rounded-sm border-gray-400
    {{ citem.varf in errfields ? 'bg-red-200 border-red-400' : '' }}"
    {{ citem.auths.required ? 'required' : '' }} />
{% endif %}
