<div class="navbar w-full h-[4em] p-[1.2em] px-[3em] text-right">
  {% for nkey, nitem in navbar %}
  <a href="{{ host~'/'~admin~'/'~nitem.slug }}" class="
    navlink text-xl
    {{ nitem.slug == args.category ? 'active' : '' }}
  ">{{ nitem.name }}</a>
  {% endfor %}
  <a href="javascript:void(0);" class="
    border border-slate-400 border-solid rounded-[50%]
    p-[0.4em] ml-[1em] bg-white
  ">
    <i class="fas fa-user fa-xl text-slate-400"></i>
  </a>
</div>
