<?php

namespace App\Http\Model\ContentModel;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{

    protected $connection = 'pgsql_main';
    public $timestamps = false;
    protected $primaryKey = 'news_id';
    protected $table = 'news';

 	 protected $fillable = [ 'news_judul','news_isi', 'news_status','news_gbr','news_gbr2','news_gbr3','news_tag', 'news_link', 'news_author', 'news_editor', 'news_category' ,'news_date'];
   
   function cleansing() {

   	$sql = "update news 
		set news_isi = replace(news_isi, '<p>

		<b></b></p><p></p>','')
		where 
		news_isi like '<p>

		<b></b></p><p></p>%';
		update news 
		set news_isi = replace(news_isi, '<p>

		</p><p>

		<b></b></p><p></p>','')
		where 
		news_isi like '<p>

		</p><p>

		<b></b></p><p></p>%';
		update news 
		set news_isi = replace(news_isi, '<p>

		<b></b></p><h1></h1>','')
		where 
		news_isi like '<p>

		<b></b></p><h1></h1>%';
		update news 
		set news_isi = replace(news_isi, '<p>

		<b></b><br></p>','')
		where 
		news_isi like '<p>

		<b></b><br></p>%';
		update news 
		set news_isi = replace(news_isi, '<p>

		<b></b><br></p>','')
		where 
		news_isi like '<p>

		<b></b><br></p>%';
		update news 
		set news_isi = replace(news_isi, '<p></p><p><br></p>','')
		where 
		news_isi like '<p></p><p><br></p>%';

		update news 
		set news_isi = replace(news_isi, '

		<p><b>','<p><b>')
		where 
		news_isi like '

		<p><b>%';
		update news 
		set news_isi = replace(news_isi, '<p>

		<b></b></p>','')
		where 
		news_isi like '<p>

		<b></b></p>%';
		update news 
		set news_isi = replace(news_isi, '<p><b></b><br></p>','')
		where 
		news_isi like '<p><b></b><br></p>%';
		update news 
		set news_isi = replace(news_isi, '<p><b></b></p><p></p>','')
		where 
		news_isi like '<p><b></b></p><p></p>%';
		update news 
		set news_isi = replace(news_isi, '<p>

		</p>','')
		where 
		news_isi like '<p>

		</p>%';
		update news set news_isi = replace(news_isi, '<b><h1></h1></b><p><b></b><b></b>','')
		where 
		news_isi like '<b><h1></h1></b><p><b></b><b></b>%';
		update news set news_isi = replace(news_isi, '<p>&nbsp;</p>','')
		where 
		news_isi ~ '<p>&nbsp;</p>';
		update news set news_isi = replace(news_isi, '<p></p>','')
		where 
		news_isi ~ '<p></p>';
		update news set news_isi = replace(news_isi, '<p><br></p>','')
		where 
		news_isi ~ '<p><br></p>';

		";
   }
}
