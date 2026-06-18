<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Province;
use App\Models\SeoConfig;
use App\Models\Setting;
use App\Models\TourOption;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MinimalSiteSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
        ]);

        $admin = User::query()->where('email', 'tuan.pn92@gmail.com')->first();
        [$savedProvinces, $savedWards] = $this->seedLocations();

        $this->seedSettings();
        $this->seedSeo();

        $savedCategories = $this->seedCategories();
        $this->seedTourOptions();
        $this->seedPosts($savedCategories, $savedProvinces, $savedWards, $admin);
        $this->seedMenus();
    }

    protected function seedLocations(): array
    {
        $savedProvinces = [];
        $savedWards = [];

        foreach ($this->provincePayloads() as $provincePayload) {
            $province = Province::query()->updateOrCreate(
                ['code' => $provincePayload['code']],
                [
                    'name' => $provincePayload['name'],
                    'type' => $provincePayload['type'],
                    'source_file' => 'seed',
                ]
            );

            $savedProvinces[$provincePayload['code']] = $province;

            foreach ($provincePayload['wards'] as $wardPayload) {
                $ward = Ward::query()->updateOrCreate(
                    ['code' => $wardPayload['code']],
                    [
                        'province_id' => $province->id,
                        'name' => $wardPayload['name'],
                        'type' => $wardPayload['type'],
                        'source_file' => 'seed',
                    ]
                );

                $savedWards[$wardPayload['code']] = $ward;
            }
        }

        return [$savedProvinces, $savedWards];
    }

    protected function seedSettings(): void
    {
        Setting::query()->updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Vietnam Homes Tourist',
                'address' => '123 Nguyen Hue, Quan 1, Ho Chi Minh',
                'email' => 'tuvan@vietnamhomestourist.vn',
                'hotline' => '0909 123 456',
                'social' => [
                    ['label' => 'Facebook', 'url' => 'https://facebook.com/'],
                    ['label' => 'Youtube', 'url' => 'https://youtube.com/'],
                ],
                'logo' => null,
                'footer_logo' => null,
                'favicon' => null,
                'footer_column_1_title' => 'Gioi thieu',
                'footer_column_1_content' => 'Don vi tu van tour, khach san va dich vu du lich.',
                'footer_column_2_title' => 'Lien he',
                'footer_column_2_content' => 'Hotline: 0909 123 456',
                'footer_column_3_title' => 'Dich vu',
                'footer_column_3_content' => 'Tour trong nuoc, tour nuoc ngoai, tu van hanh trinh.',
                'footer_column_4_title' => 'Ho tro',
                'footer_column_4_content' => 'Tiep nhan yeu cau tu van va dat lich nhanh.',
            ]
        );
    }

    protected function seedSeo(): void
    {
        foreach ([
            'home' => [
                'title' => 'Trang chu',
                'description' => 'Tour du lich, khach san va dich vu tu van hanh trinh.',
                'keywords' => 'du lich, tour, khach san, hanh trinh',
            ],
            'about' => [
                'title' => 'Gioi thieu',
                'description' => 'Thong tin gioi thieu ve Vietnam Homes Tourist.',
                'keywords' => 'gioi thieu, vietnam homes tourist',
            ],
            'contact' => [
                'title' => 'Lien he',
                'description' => 'Lien he tu van tour va dich vu du lich.',
                'keywords' => 'lien he, tu van tour, du lich',
            ],
        ] as $pageKey => $payload) {
            SeoConfig::query()->updateOrCreate(['page_key' => $pageKey], $payload);
        }
    }

    protected function seedCategories(): array
    {
        $savedCategories = [];

        foreach ($this->categoryPayloads() as $slug => $payload) {
            $savedCategories[$slug] = Category::query()->updateOrCreate(
                ['slug' => $slug],
                $payload + [
                    'parent_id' => null,
                    'is_active' => true,
                ]
            );
        }

        return $savedCategories;
    }

    protected function seedTourOptions(): void
    {
        foreach ($this->tourOptionPayloads() as $groupKey => $names) {
            foreach ($names as $index => $name) {
                TourOption::query()->updateOrCreate(
                    [
                        'group_key' => $groupKey,
                        'name' => $name,
                    ],
                    [
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    protected function seedPosts(array $savedCategories, array $savedProvinces, array $savedWards, ?User $admin): void
    {
        foreach ($this->postPayloads() as $payload) {
            $category = $savedCategories[$payload['category_slug']];
            $province = isset($payload['province_code']) ? ($savedProvinces[$payload['province_code']] ?? null) : null;
            $ward = isset($payload['ward_code']) ? ($savedWards[$payload['ward_code']] ?? null) : null;

            $post = Post::query()->updateOrCreate(
                ['slug' => $payload['slug']],
                [
                    'type' => $payload['type'],
                    'category_id' => $category->id,
                    'seller_id' => $payload['type'] === Post::TYPE_PRODUCT ? $admin?->id : null,
                    'title' => $payload['title'],
                    'seo_title' => $payload['title'],
                    'seo_description' => $payload['summary'] ?? null,
                    'summary' => $payload['summary'] ?? null,
                    'content' => $payload['content'] ?? null,
                    'sales_policy' => $payload['sales_policy'] ?? null,
                    'address' => $payload['address'] ?? null,
                    'itinerary' => $payload['itinerary'] ?? null,
                    'departure_location' => $payload['departure_location'] ?? null,
                    'destination' => $payload['destination'] ?? null,
                    'departure_date' => $payload['departure_date'] ?? null,
                    'attractions' => $payload['attractions'] ?? null,
                    'transport' => $payload['transport'] ?? null,
                    'duration' => $payload['duration'] ?? null,
                    'guide_content' => $payload['guide_content'] ?? null,
                    'visa_content' => $payload['visa_content'] ?? null,
                    'insurance_content' => $payload['insurance_content'] ?? null,
                    'promotion_content' => $payload['promotion_content'] ?? null,
                    'province_id' => $province?->id,
                    'ward_id' => $ward?->id,
                    'map_embed' => $payload['map_embed'] ?? null,
                    'image' => $payload['image'] ?? null,
                    'price' => $payload['price'] ?? null,
                    'is_active' => true,
                    'is_featured' => $payload['is_featured'] ?? false,
                    'published_at' => $payload['published_at'] ?? Carbon::now(),
                ]
            );

            if (($payload['type'] ?? null) !== Post::TYPE_PRODUCT) {
                continue;
            }

            foreach (($payload['gallery'] ?? []) as $index => $imagePath) {
                PostImage::query()->updateOrCreate(
                    [
                        'post_id' => $post->id,
                        'image' => $imagePath,
                    ],
                    [
                        'sort_order' => $index + 1,
                        'image_type' => PostImage::TYPE_PERSPECTIVE,
                    ]
                );
            }
        }
    }

    protected function seedMenus(): void
    {
        foreach ($this->menuPayloads() as $menu) {
            Menu::query()->updateOrCreate(
                ['slug' => $menu['slug']],
                [
                    'parent_id' => null,
                    'name' => $menu['name'],
                    'target' => '_self',
                    'sort_order' => $menu['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }

    protected function provincePayloads(): array
    {
        return [
            [
                'code' => '79',
                'name' => 'Ho Chi Minh',
                'type' => 'Thanh pho',
                'wards' => [
                    ['code' => '760', 'name' => 'Ben Nghe', 'type' => 'Phuong'],
                    ['code' => '771', 'name' => 'Da Kao', 'type' => 'Phuong'],
                ],
            ],
            [
                'code' => '48',
                'name' => 'Da Nang',
                'type' => 'Thanh pho',
                'wards' => [
                    ['code' => '20194', 'name' => 'Hai Chau 1', 'type' => 'Phuong'],
                    ['code' => '20200', 'name' => 'Thach Thang', 'type' => 'Phuong'],
                ],
            ],
            [
                'code' => '01',
                'name' => 'Ha Noi',
                'type' => 'Thanh pho',
                'wards' => [
                    ['code' => '00001', 'name' => 'Phuc Xa', 'type' => 'Phuong'],
                    ['code' => '00004', 'name' => 'Truc Bach', 'type' => 'Phuong'],
                ],
            ],
        ];
    }

    protected function categoryPayloads(): array
    {
        return [
            'du-lich-trong-nuoc' => [
                'type' => Category::TYPE_PRODUCT,
                'name' => 'Du lich trong nuoc',
                'description' => 'Tong hop cac tour noi dia noi bat voi lich trinh linh hoat.',
                'seo_title' => 'Tour du lich trong nuoc',
                'seo_description' => 'Cac chuong trinh du lich trong nuoc moi nhat.',
                'sort_order' => 1,
            ],
            'du-lich-nuoc-ngoai' => [
                'type' => Category::TYPE_PRODUCT,
                'name' => 'Du lich nuoc ngoai',
                'description' => 'Danh muc tour quoc te voi cac diem den pho bien va dich vu tron goi.',
                'seo_title' => 'Tour du lich nuoc ngoai',
                'seo_description' => 'Cac hanh trinh du lich nuoc ngoai noi bat.',
                'sort_order' => 2,
            ],
            'tour-gia-dinh' => [
                'type' => Category::TYPE_PRODUCT,
                'name' => 'Tour gia dinh',
                'description' => 'Cac tour uu tien nghi duong, di chuyen nhe va lich trinh can bang.',
                'seo_title' => 'Tour gia dinh',
                'seo_description' => 'Goi y tour gia dinh de di chuyen va nghi duong thoai mai.',
                'sort_order' => 3,
            ],
            'tin-du-lich' => [
                'type' => Category::TYPE_NEWS,
                'name' => 'Tin du lich',
                'description' => 'Tin tuc, kinh nghiem va cap nhat moi tu thi truong du lich.',
                'seo_title' => 'Tin tuc du lich',
                'seo_description' => 'Bai viet va tin tuc du lich moi nhat.',
                'sort_order' => 1,
            ],
        ];
    }

    protected function tourOptionPayloads(): array
    {
        return [
            TourOption::GROUP_TRANSPORT => [
                'May bay',
                'Xe du lich',
                'Tau hoa',
                'Tau cao toc',
            ],
            TourOption::GROUP_LOCATION => [
                'Ho Chi Minh',
                'Ha Noi',
                'Da Nang',
                'Can Tho',
            ],
        ];
    }

    protected function postPayloads(): array
    {
        $publishedAt = Carbon::now()->subDay();

        return [
            [
                'type' => Post::TYPE_PRODUCT,
                'category_slug' => 'du-lich-trong-nuoc',
                'title' => 'Tour Da Nang 3 ngay 2 dem',
                'slug' => 'tour-da-nang-3-ngay-2-dem',
                'summary' => 'Chuong trinh nghi duong ket hop tham quan Da Nang va Hoi An.',
                'content' => '<p>Tour Da Nang 3 ngay 2 dem phu hop gia dinh va nhom ban nho, ket hop nghi duong bien va tham quan pho co.</p>',
                'sales_policy' => '<ul><li>Gia da gom khach san, xe dua don va ve tham quan theo chuong trinh.</li><li>Khong gom chi phi ca nhan va VAT.</li></ul>',
                'guide_content' => '<p>Huong dan vien don doan tai diem hen, ho tro xuyen suot hanh trinh.</p>',
                'visa_content' => '<p>Tour trong nuoc khong can visa.</p>',
                'insurance_content' => '<p>Co bao hiem du lich co ban trong suot hanh trinh.</p>',
                'promotion_content' => '<p>Giam 5 phan tram cho nhom tu 4 khach tro len.</p>',
                'itinerary' => 'Da Nang - Ba Na - Hoi An',
                'attractions' => 'Ba Na Hills, Cau Vang, Pho co Hoi An, Bien My Khe',
                'departure_location' => 'TP. HCM',
                'destination' => 'Da Nang',
                'departure_date' => Carbon::now()->addDays(10)->toDateString(),
                'transport' => 'May bay',
                'duration' => '3 ngay 2 dem',
                'price' => 3590000,
                'image' => 'tourit/assets/img/tour/01.jpg',
                'province_code' => '48',
                'ward_code' => '20194',
                'address' => 'Trung tam Hai Chau, Da Nang',
                'map_embed' => '<iframe src="https://www.google.com/maps?q=Da%20Nang&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'is_featured' => true,
                'published_at' => $publishedAt,
                'gallery' => [
                    'tourit/assets/img/tour/01.jpg',
                    'tourit/assets/img/tour/02.jpg',
                    'tourit/assets/img/tour/03.jpg',
                ],
            ],
            [
                'type' => Post::TYPE_PRODUCT,
                'category_slug' => 'du-lich-nuoc-ngoai',
                'title' => 'Tour Han Quoc mua thu 5 ngay 4 dem',
                'slug' => 'tour-han-quoc-mua-thu-5-ngay-4-dem',
                'summary' => 'Hanh trinh tham quan Seoul, Nami va Everland.',
                'content' => '<p>Tour Han Quoc mua thu dua khach tham quan Seoul, dao Nami va khu vui choi Everland.</p>',
                'sales_policy' => '<ul><li>Gia gom ve may bay, khach san tieu chuan va an theo chuong trinh.</li><li>Khong gom tien boi duong huong dan vien.</li></ul>',
                'guide_content' => '<p>Ho tro truoc chuyen di, nhac lich hen va thong tin nhap canh.</p>',
                'visa_content' => '<p>Ho tro huong dan ho so xin visa theo danh sach giay to can thiet.</p>',
                'insurance_content' => '<p>Bao hiem du lich quoc te theo goi co ban.</p>',
                'promotion_content' => '<p>Uu dai cho khach dat truoc 30 ngay.</p>',
                'itinerary' => 'Seoul - Nami - Everland',
                'attractions' => 'Gyeongbokgung, Nami, Everland, Myeongdong',
                'departure_location' => 'TP. HCM',
                'destination' => 'Han Quoc',
                'departure_date' => Carbon::now()->addDays(25)->toDateString(),
                'transport' => 'May bay',
                'duration' => '5 ngay 4 dem',
                'price' => 15990000,
                'image' => 'tourit/assets/img/tour/04.jpg',
                'province_code' => '79',
                'ward_code' => '760',
                'address' => 'Van phong Quan 1, Ho Chi Minh',
                'map_embed' => '<iframe src="https://www.google.com/maps?q=Ho%20Chi%20Minh&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'is_featured' => true,
                'published_at' => $publishedAt->copy()->subHours(2),
                'gallery' => [
                    'tourit/assets/img/tour/04.jpg',
                    'tourit/assets/img/tour/05.jpg',
                    'tourit/assets/img/tour/06.jpg',
                ],
            ],
            [
                'type' => Post::TYPE_PRODUCT,
                'category_slug' => 'tour-gia-dinh',
                'title' => 'Tour Ha Noi Ninh Binh 4 ngay 3 dem',
                'slug' => 'tour-ha-noi-ninh-binh-4-ngay-3-dem',
                'summary' => 'Chuoi diem den phu hop gia dinh voi lich trinh de di chuyen.',
                'content' => '<p>Tour ket hop tham quan Ha Noi va Ninh Binh, uu tien cac diem den de tiep can voi tre nho va nguoi lon tuoi.</p>',
                'sales_policy' => '<p>Chinh sach dat coc linh hoat, xac nhan giu cho sau khi thanh toan.</p>',
                'guide_content' => '<p>Co huong dan vien dia phuong va hotline ho tro suot tuyen.</p>',
                'visa_content' => '<p>Khong ap dung visa doi voi tour trong nuoc.</p>',
                'insurance_content' => '<p>Da bao gom bao hiem du lich muc co ban.</p>',
                'promotion_content' => '<p>Tang bua toi dac san cho gia dinh dat som.</p>',
                'itinerary' => 'Ha Noi - Trang An - Bai Dinh - Hoan Kiem',
                'attractions' => 'Trang An, Bai Dinh, Pho co Ha Noi, Ho Hoan Kiem',
                'departure_location' => 'Ha Noi',
                'destination' => 'Ninh Binh',
                'departure_date' => Carbon::now()->addDays(18)->toDateString(),
                'transport' => 'Xe du lich',
                'duration' => '4 ngay 3 dem',
                'price' => 4890000,
                'image' => 'tourit/assets/img/tour/07.jpg',
                'province_code' => '01',
                'ward_code' => '00004',
                'address' => 'Pho co Ha Noi',
                'map_embed' => '<iframe src="https://www.google.com/maps?q=Ha%20Noi&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                'is_featured' => false,
                'published_at' => $publishedAt->copy()->subHours(6),
                'gallery' => [
                    'tourit/assets/img/tour/07.jpg',
                    'tourit/assets/img/tour/08.jpg',
                    'tourit/assets/img/tour/03.jpg',
                ],
            ],
            [
                'type' => Post::TYPE_NEWS,
                'category_slug' => 'tin-du-lich',
                'title' => 'Kinh nghiem chuan bi ho so du lich nuoc ngoai',
                'slug' => 'kinh-nghiem-chuan-bi-ho-so-du-lich-nuoc-ngoai',
                'summary' => 'Nhung luu y can biet truoc khi dang ky tour quoc te.',
                'content' => '<p>Bai viet tong hop nhung thong tin can chuan bi khi di tour nuoc ngoai, tu ho so ca nhan den lich hen nop giay to.</p>',
                'image' => 'uploads/posts/20260615030504-J7spXBnlCU.webp',
                'published_at' => $publishedAt->copy()->subHours(4),
            ],
        ];
    }

    protected function menuPayloads(): array
    {
        return [
            ['name' => 'Du lich trong nuoc', 'slug' => 'du-lich-trong-nuoc', 'sort_order' => 1],
            ['name' => 'Du lich nuoc ngoai', 'slug' => 'du-lich-nuoc-ngoai', 'sort_order' => 2],
            ['name' => 'Tour gia dinh', 'slug' => 'tour-gia-dinh', 'sort_order' => 3],
            ['name' => 'Tin tuc', 'slug' => 'tin-tuc', 'sort_order' => 4],
            ['name' => 'Gioi thieu', 'slug' => 'gioi-thieu', 'sort_order' => 5],
            ['name' => 'Lien he', 'slug' => 'lien-he', 'sort_order' => 6],
        ];
    }
}
