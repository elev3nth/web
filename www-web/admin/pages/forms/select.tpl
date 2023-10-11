
{% if args.page is not defined or
      args.page == list or
      args.page == view
%}
  {% if args.page == view %}
    {{ content.data[citem.flds] }}
  {% else %}
    {{ ditem[citem.flds] }}
  {% endif %}
{% else %}
  <select class="w-full h-[2em] p-2">
    <option value="{{ content.data[citem.flds] }}">
      {{ content.data[citem.flds] }}
    </option>
  </select>
{% endif %}
