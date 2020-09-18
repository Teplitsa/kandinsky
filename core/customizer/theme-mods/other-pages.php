<?php
/**
 * Global Settings
 *
 * @package Kandinsky
 */

/* Other Pages Section */
Kirki::add_section(
	'other_pages',
	array(
		'title'    => esc_html__( 'Create other pages', 'knd' ),
		'priority' => 5,
	)
);

$other_pages_desc = '<p>' . sprintf( __( 'Если вам нужно создать новую страницу – кликните на кнопку ниже. Страницы управляются в разделе «<a href="%s">Меню > Страницы</a>». Вы можете редактировать содержимое страниц, создавать и удалять страницы.', 'knd' ), admin_url( 'edit.php?post_type=page' ) ) . '</p>

<p><a href="' . admin_url( 'post-new.php?post_type=page' ) . '" class="button">' . esc_html__( 'Add new page', 'knd' ) . '</a></p>

<p>' . __( 'Помимо страниц в WordPress используются «Записи» – используйте их для создания новостей.', 'knd' ) . '</p>

<p><a href="' . get_admin_url( null, 'post-new.php?post_type=page' ) . '" class="button">' . esc_html__( 'Go to posts page', 'knd' ) . '</a></p>

<p>' . __( 'Если же вам нужно создать совершенно новые сущности (Например, «Заявки» или «Мероприятия») вы можете создать т.н. custom post type (пользовательский тип записи), например, с помощью плагина Custom Post Type UI <a href="https://ru.wordpress.org/plugins/custom-post-type-ui/" target="_blank">https://ru.wordpress.org/plugins/custom-post-type-ui/</a>', 'knd' ) . '</p>

';

Kirki::add_field( 'knd_theme_mod', array(
	'type'     => 'custom',
	'settings' => 'other_pages_create',
	'section'  => 'other_pages',
	'default'  => $other_pages_desc,
	'priority' => 1,
) );
