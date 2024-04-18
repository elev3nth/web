{% set optcfg = citem.opts.conf %}
{% set optval = citem.opts.data %}
{% if args.page == view or args.page == 'edit' %}
  {% set optvalue = content.data[citem.flds] %}
{% else %}
  {% set optvalue = ditem[citem.flds] %}
{% endif %}
{% if args.page is not defined or
      args.page == list or
      args.page == view
%}
    {% for optskey, opts in optval %}
      {% if opts[optcfg.db.prefix~optcfg.db.uuidkey] == optvalue %}
        {% for optk, optc in optcfg.columns %}
          {% if (optc.title|default(false) == true) %}
            {% if citem.link|default(false) == true and args.page != view %}
              <a href="{{ host~'/'~admin~'/'~links|join('/')~'/'~view~'/'~
              ditem[content.table.pfx~content.table.key] }}" class="underline">
                {{ opts[optcfg.db.prefix~optc.name] }}
              </a>
            {% else %}
              {{ opts[optcfg.db.prefix~optc.name] }}
            {% endif %}
          {% endif %}
        {% endfor %}
      {% endif %}
    {% endfor %}
{% else %}
  <select
    id="{{ citem.varf }}"
    name="{{ citem.varf }}"
    class="w-full h-[2em] p-1 rounded-sm border border-gray-400
    {{ citem.varf in errfields ? 'bg-red-200 border-red-400' : '' }}"
    {{ citem.auths.required ? 'required' : '' }}>
    {% if optvalue is empty %}
      <option value="">
        Please Select A  {{ content.title.singular }}
      </option>
    {% endif %}
    {% for optskey, opts in optval %}
      {% if opts[optcfg.db.prefix~optcfg.db.uuidkey] == optvalue %}
        {% for optk, optc in optcfg.columns %}
          {% if (optc.title|default(false) == true) %}
            <option value="{{ opts[optcfg.db.prefix~optcfg.db.uuidkey] }}"
              selected="selected">
              {{ opts[optcfg.db.prefix~optc.name] }}
            </option>
          {% endif %}
        {% endfor %}
      {% else %}
        {% for optk, optc in optcfg.columns %}
          {% if (optc.title|default(false) == true) %}
            <option
              value="{{ opts[optcfg.db.prefix~optcfg.db.uuidkey] }}"
            >
              {{ opts[optcfg.db.prefix~optc.name] }}
            </option>
          {% endif %}
        {% endfor %}
      {% endif %}
    {% endfor %}
  </select>
{% endif %}
