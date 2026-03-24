# Team Contributions - Urbanease Check ITW Symfony

## Project Overview
Treasure hunt game built with Symfony 4.4. Players navigate a boat on a map grid, exploring tiles to find hidden treasure.

## Contributors

### 1. **Nicolas-CARRERE** (Original Author)
**Email:** nicolascarrere.pro@gmail.com / 105366773+Nicolas-CARRERE@users.noreply.github.com

**What he built:**
- Initial project setup (Symfony 4.4)
- Core entities: `Boat`, `Tile`
- Base controllers: `HomeController`, `MapController`, `BoatController`, `TileController`
- Database structure and migrations
- Initial game mechanics (steps 1-12):
  - Map rendering with coordinates
  - Tile type display
  - Movement validation (boundary checks)
  - Tile existence checking
  - Treasure system
  - Flash messages for user feedback
  - Random island generation
- Twig templates for the game interface
- Configuration (.env, routes, services)

**Branches:** `main`, `test_nc`

---

### 2. **nco-bot-helper** (AI Agent / OpenClaw)
**Email:** nco-bot-helper@openclaw.local

**What I built:**
- Continued development on steps 4-12
- Enhanced tile existence logic
- Improved movement boundary checks with user alerts
- Implemented `hasTreasure` attribute on Tile entity
- Built `getRandomIsland()` method in MapManager
- Implemented `checkTreasure()` method for win condition
- Added flash message on treasure discovery
- Code cleanup and refactoring

**Branch:** `NCO-BOT-HELPER`

---

### 3. **Clément Lopez** (Minor Contributor)
**Email:** Not specified

**What he did:**
- Fixed `.env` database example configuration
- Initial commit foundation

---

## Git Branch Summary

| Branch | Author | Status |
|--------|--------|--------|
| `main` | Nicolas-CARRERE | Active development |
| `NCO-BOT-HELPER` | nco-bot-helper | AI agent work |
| `test_nc` | Nicolas-CARRERE | Testing branch |

---

## Technical Stack
- **Framework:** Symfony 4.4.*
- **PHP:** 8.0+ (tested on 8.3.6)
- **Database:** Doctrine ORM with MySQL/PostgreSQL
- **Frontend:** Twig templates + vanilla JS
- **Testing:** PHPUnit 9.5

---

## Game Flow
1. Player starts on a random island tile
2. Navigate using direction buttons (N/S/E/W)
3. System checks if tile exists and displays type
4. Boundary validation prevents leaving map
5. Random treasure hidden on an island tile
6. `checkTreasure()` triggers win condition
7. Flash message announces victory

---

## Setup Status
✅ Repo cloned successfully  
✅ Dependencies downloaded (composer install)  
⚠️ **Blocked:** PHP DOM extension required for cache:clear  
⚠️ **Version mismatch:** Some locked packages require PHP <8.2, we have 8.3.6

**Next steps:**
- Install PHP DOM extension (`apt-get install php8.3-xml`)
- Or run with `--ignore-platform-reqs` flag
- Create `.env.local` with DB credentials
- Run migrations
- Start Symfony server

---

*Generated: 2026-03-24*
