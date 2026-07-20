# Hekim sitesi deploy

Ana platform: `randevuajandam-site` + `randevuajandam-api`.  
Bu repo: bireysel hekim vitrin + CMS (API key ile).

## Sunucu

```powershell
# PC'den
cd deploy
.\deploy.ps1 -Target hekim
```

Laravel kök: `/home/u195737737/apps/randevuajandam-hekim`

## .env kritik

```env
RANDEVU_API_PLATFORM=https://api.DOMAIN/api/v1
RANDEVU_API_BASE_URL=https://api.DOMAIN/api/v1/public
RANDEVU_API_KEY=...
RANDEVU_API_SECRET=...
```

Panel: `/yonetim` → API entegrasyon + Site Ayarları.
