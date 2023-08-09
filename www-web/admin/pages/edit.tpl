{% for ckey, citem in content.columns %}
  <div class="lg:flex">
    <div class="
      text-right align-middle py-[0.4em] my-1
      lg:flex-none lg:w-[100px]
    ">
      {{ citem.name|title }}
    </div>
    <div class="text-left mx-3 my-1 lg:flex-1">
      {% include [
        '/forms/'~citem.type~'.tpl',
        '/forms/text.tpl'
      ] %}
    </div>
  </div>
{% endfor %}
