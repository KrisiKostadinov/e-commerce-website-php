<nav class="h-[60px] flex items-center justify-center shadow">
    <ul>
        <li>
            <a href="/">Начало</a>
        </li>
        <li>
            <a href="/about">За нас</a>
        </li>
        <li>
            <a href="/products">Продукти</a>
        </li>
        <li>
            <a href="/categories">Категории</a>
        </li>
        <li>
            <a href="/contacts">Контакти</a>
        </li>
        <?php if (empty($user)): ?>
            <li>
                <a href="/auth/login">Вход</a>
            </li>
            <li>
                <a href="/auth/register">Регистрация</a>
            </li>
        <?php else: ?>
            <li>
                <a href="/auth/profile">Профил</a>
            </li>
            <li>
                <a href="/auth/logout?_method=DELETE">Изход</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>