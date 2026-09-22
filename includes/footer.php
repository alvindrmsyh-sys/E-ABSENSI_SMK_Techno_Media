</main>

            <footer class="border-t border-slate-200 bg-white px-8 py-6 text-sm text-slate-500">
                &copy; <?php echo date('Y'); ?> E-ABSENSI
            </footer>

        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuBtn = document.getElementById('menuBtn');

        if(menuBtn){
            menuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
        }

        if(overlay){
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }

        // LOGIKA TRANSISI HALUS (FADE IN/OUT)
        // Fade in saat halaman dimuat
        window.addEventListener('load', () => document.body.classList.add('loaded'));

        // Fade out saat link diklik
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Hanya jalankan jika bukan link hash dan bukan buka tab baru
                if (!this.hash && !this.target) {
                    e.preventDefault();
                    document.body.classList.remove('loaded');
                    setTimeout(() => window.location = this.href, 400);
                }
            });
        });
    </script>
</body>
</html>