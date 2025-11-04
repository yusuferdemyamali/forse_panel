<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use App\Models\Blog; 
use Carbon\Carbon;
use Spatie\Sitemap\Sitemap;

	class GenerateSitemap extends Command
	{
		/**
		 * Komutun imzası (yani terminalde nasıl çağrılacağı).
		 * Terminalde: php artisan sitemap:generate
		 *
		 * @var string
		 */
		protected $signature = 'sitemap:generate';

		/**
		 * Komutun kısa açıklaması.
		 *
		 * @var string
		 */
		protected $description = 'Veritabanındaki tüm sayfaları ve dinamik içerikleri içeren sitemap.xml dosyasını oluşturur.';

		/**
		 * Komutun yürütüldüğü yer.
		 *
		 * @return int
		 */
		public function handle()
		{
			$this->info('Sitemap oluşturuluyor...');

			// 1. SitemapGenerator nesnesini, uygulamanın temel URL'si ile oluştur.
			$sitemap = Sitemap::create(config('app.url'));

			// 2. Statik Sayfaları Ekleme (Manuel olarak eklediğiniz sayfalar)
			// Ana Sayfa
			$sitemap->add(
				Url::create('/')
				   ->setPriority(1.0) // En yüksek öncelik
				   ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY) // Sık sık değişebilir
			);

			// PDF veya diğer statik dosyalar (Sizin örneğinizden alındı)
			$sitemap->add(
				Url::create('/cart')
				   ->setPriority(0.8)
				   ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
				   ->setLastModificationDate(Carbon::parse('2025-10-14T09:40:32+00:00'))
			);

			// 3. Dinamik İçerikleri (Ürünleri) Veritabanından Çekip Ekleme
			// Bu, sizin "products" bölümünüzdeki dinamik içerikleri ekler.
			$blogs = Blog::select('slug', 'updated_at')->get();

			$blogs->each(function (Blog $blog) use ($sitemap) {
				$sitemap->add(
					Url::create("/blog/{$blog->slug}") // URL yapısını kendi rotalarınıza göre ayarlayın!
					   ->setLastModificationDate($blog->updated_at)
					   ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
					   ->setPriority(0.7)
				);
			});

			// 4. Sitemap dosyasını "public" klasörüne yazdır.
			$sitemap->writeToFile(public_path('sitemap.xml'));

			$this->info('Sitemap başarıyla oluşturuldu. Toplam URL sayısı: ' . count($sitemap->getTags()));

			return Command::SUCCESS;
		}
	}