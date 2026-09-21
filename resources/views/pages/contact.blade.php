<x-layouts.app title="Contact Us - UM Rank Predictor">

    <div class="bg-slate-900 text-white py-10 border-b border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-extrabold tracking-tight">Contact Support</h1>
            <p class="text-slate-400 text-sm mt-2">Have questions or feedback about rank predictions? Reach out to us.</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-10 shadow-sm">
            
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you for your message! Our team will get back to you shortly.');" class="space-y-6">
                <div>
                    <label for="c_name" class="block text-sm font-semibold text-slate-700 mb-1">Your Name</label>
                    <input type="text" id="c_name" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm" required>
                </div>

                <div>
                    <label for="c_email" class="block text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                    <input type="email" id="c_email" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm" required>
                </div>

                <div>
                    <label for="c_subject" class="block text-sm font-semibold text-slate-700 mb-1">Subject / Exam Inquiry</label>
                    <input type="text" id="c_subject" placeholder="e.g. SSC CGL 2026 Prediction Query" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm" required>
                </div>

                <div>
                    <label for="c_message" class="block text-sm font-semibold text-slate-700 mb-1">Message</label>
                    <textarea id="c_message" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 text-sm" required></textarea>
                </div>

                <button type="submit" class="w-full py-4 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-base shadow-md transition-all">
                    Send Message
                </button>
            </form>

        </div>
    </div>

</x-layouts.app>
