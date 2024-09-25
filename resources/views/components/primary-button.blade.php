<button 
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150']) }}
    style="background-color: #3ca88b; outline: none;" 
    onmouseover="this.style.backgroundColor='#349e7f';" 
    onmouseout="this.style.backgroundColor='#3ca88b';"
    onfocus="this.style.outline='none'; this.style.boxShadow='none';" 
    onblur="this.style.outline=''; this.style.boxShadow='';"
>
    {{ $slot }}
</button>
