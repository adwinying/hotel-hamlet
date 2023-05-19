{
  description = "Hotel Hamlet";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system: let
      pkgs = nixpkgs.legacyPackages.${system};
      basePkgs = with pkgs; [
        envsubst
        nginx
        php82
        php82Packages.composer
        nodejs_16
      ];
    in {
      defaultPackage = pkgs.stdenv.mkDerivation {
        name = "hotel-hamlet";
        src = builtins.path { path = ./.; name = "src"; };
        buildInputs = basePkgs ++ [ pkgs.git pkgs.curl ];

        shellHook = ''
          echo "`${pkgs.php82}/bin/php --version`"
          echo "`${pkgs.php82Packages.composer}/bin/composer --version`"
          echo "node: `${pkgs.nodejs_16}/bin/node --version`"
          echo "npm: v`${pkgs.nodejs_16}/bin/npm --version`"
        '';

        buildPhase = ''
          #composer install --optimize-autoloader --no-cache --no-interaction --no-scripts --prefer-dist
          ls -la
          php artisan
          php artisan typescript:transform --force --output js/generated.d.ts
          ASSET_CMD="build" npm run build
        '';

        installPhase = ''
          mkdir -p $out/src
          cp -r . $out/src
        '';
      };
    });
}
