{
  description = "Hotel Hamlet";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system: let
      pkgs = nixpkgs.legacyPackages.${system};
    in {
      devShell = with pkgs; mkShell {
        buildInputs = [
          envsubst
          nginx
          php82
          php82Packages.composer
          nodejs_18
          mprocs
        ];

        shellHook = ''
          echo "`${pkgs.php82}/bin/php --version`"
          echo "`${pkgs.php82Packages.composer}/bin/composer --version`"
          echo "node: `${pkgs.nodejs_18}/bin/node --version`"
          echo "npm: v`${pkgs.nodejs_18}/bin/npm --version`"
        '';
      };
    });
}
